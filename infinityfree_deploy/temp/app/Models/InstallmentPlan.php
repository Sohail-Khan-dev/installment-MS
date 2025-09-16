<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InstallmentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_number',
        'customer_id',
        'product_id',
        'total_amount',
        'down_payment',
        'financed_amount',
        'number_of_installments',
        'installment_amount',
        'interest_rate',
        'start_date',
        'end_date',
        'payment_frequency',
        'status',
        'total_paid',
        'balance',
        'payments_made',
        'next_payment_date',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'next_payment_date' => 'date',
        'total_amount' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'financed_amount' => 'decimal:2',
        'installment_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($plan) {
            $plan->plan_number = 'PLAN-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $plan->financed_amount = $plan->total_amount - $plan->down_payment;
            $plan->balance = $plan->financed_amount;
            $plan->calculateInstallmentAmount();
            $plan->calculateEndDate();
            $plan->next_payment_date = $plan->start_date;
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function calculateInstallmentAmount()
    {
        if ($this->interest_rate > 0) {
            $monthlyRate = $this->interest_rate / 100 / 12;
            $this->installment_amount = $this->financed_amount * 
                ($monthlyRate * pow(1 + $monthlyRate, $this->number_of_installments)) / 
                (pow(1 + $monthlyRate, $this->number_of_installments) - 1);
        } else {
            $this->installment_amount = $this->financed_amount / $this->number_of_installments;
        }
    }

    public function calculateEndDate()
    {
        $startDate = Carbon::parse($this->start_date);
        
        switch ($this->payment_frequency) {
            case 'weekly':
                $this->end_date = $startDate->addWeeks($this->number_of_installments);
                break;
            case 'bi-weekly':
                $this->end_date = $startDate->addWeeks($this->number_of_installments * 2);
                break;
            case 'monthly':
                $this->end_date = $startDate->addMonths($this->number_of_installments);
                break;
            case 'quarterly':
                $this->end_date = $startDate->addMonths($this->number_of_installments * 3);
                break;
        }
    }

    public function updateNextPaymentDate()
    {
        $lastPaymentDate = Carbon::parse($this->next_payment_date);
        
        switch ($this->payment_frequency) {
            case 'weekly':
                $this->next_payment_date = $lastPaymentDate->addWeek();
                break;
            case 'bi-weekly':
                $this->next_payment_date = $lastPaymentDate->addWeeks(2);
                break;
            case 'monthly':
                $this->next_payment_date = $lastPaymentDate->addMonth();
                break;
            case 'quarterly':
                $this->next_payment_date = $lastPaymentDate->addMonths(3);
                break;
        }
        
        $this->save();
    }

    public function recordPayment($amount)
    {
        $this->total_paid += $amount;
        $this->balance -= $amount;
        $this->payments_made++;
        
        if ($this->balance <= 0) {
            $this->status = 'completed';
            $this->balance = 0;
        } else {
            $this->updateNextPaymentDate();
        }
        
        $this->save();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'active')
            ->where('next_payment_date', '<', now());
    }

    public function isOverdue()
    {
        return $this->status === 'active' && $this->next_payment_date < now();
    }

    public function getDaysOverdueAttribute()
    {
        if ($this->isOverdue()) {
            return now()->diffInDays($this->next_payment_date);
        }
        return 0;
    }
}
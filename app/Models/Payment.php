<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number',
        'installment_plan_id',
        'customer_id',
        'amount',
        'payment_date',
        'due_date',
        'payment_method',
        'status',
        'reference_number',
        'late_fee',
        'notes',
        'receipt_number'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            $payment->payment_number = 'PAY-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            if (!$payment->receipt_number) {
                $payment->receipt_number = 'REC-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
        });

        static::created(function ($payment) {
            if ($payment->status === 'paid') {
                $payment->installmentPlan->recordPayment($payment->amount);
            }
        });
    }

    public function installmentPlan()
    {
        return $this->belongsTo(InstallmentPlan::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function markAsPaid()
    {
        $this->status = 'paid';
        $this->payment_date = now();
        $this->save();
        
        $this->installmentPlan->recordPayment($this->amount);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
            ->where('due_date', '<', now());
    }

    public function isOverdue()
    {
        return $this->status === 'pending' && $this->due_date < now();
    }

    public function calculateLateFee($lateFeePercentage = 5)
    {
        if ($this->isOverdue()) {
            $daysLate = now()->diffInDays($this->due_date);
            $this->late_fee = ($this->amount * $lateFeePercentage / 100) * ceil($daysLate / 30);
            $this->save();
        }
    }

    public function getTotalAmountAttribute()
    {
        return $this->amount + $this->late_fee;
    }
}
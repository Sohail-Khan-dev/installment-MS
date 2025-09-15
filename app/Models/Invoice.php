<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'installment_plan_id',
        'customer_id',
        'payment_id',
        'invoice_date',
        'due_date',
        'amount',
        'tax_amount',
        'total_amount',
        'status',
        'description',
        'notes'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $invoice->invoice_number = 'INV-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $invoice->total_amount = $invoice->amount + $invoice->tax_amount;
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

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function markAsPaid($paymentId = null)
    {
        $this->status = 'paid';
        if ($paymentId) {
            $this->payment_id = $paymentId;
        }
        $this->save();
    }

    public function markAsOverdue()
    {
        if ($this->status === 'sent' && $this->due_date < now()) {
            $this->status = 'overdue';
            $this->save();
        }
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
            ->orWhere(function ($q) {
                $q->where('status', 'sent')
                  ->where('due_date', '<', now());
            });
    }

    public function sendToCustomer()
    {
        // Logic to send invoice via email or SMS
        $this->status = 'sent';
        $this->save();
        
        // Here you would implement email/SMS sending logic
        // For now, we'll just return true
        return true;
    }
}
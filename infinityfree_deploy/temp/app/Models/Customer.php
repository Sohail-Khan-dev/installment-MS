<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'cnic',
        'date_of_birth',
        'credit_limit',
        'status'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'credit_limit' => 'decimal:2',
    ];

    public function installmentPlans()
    {
        return $this->hasMany(InstallmentPlan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getTotalOutstandingAttribute()
    {
        return $this->installmentPlans()
            ->where('status', 'active')
            ->sum('balance');
    }

    public function getAvailableCreditAttribute()
    {
        return $this->credit_limit - $this->total_outstanding;
    }

    public function canTakeCredit($amount)
    {
        return $this->available_credit >= $amount;
    }
}
<?php
// app/Models/Customer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'address',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getTotalInvoiceAmountAttribute(): float
    {
        return $this->invoices->sum('amount');
    }

    public function getPaidInvoiceAmountAttribute(): float
    {
        return $this->invoices->where('status', 'Paid')->sum('amount');
    }
}
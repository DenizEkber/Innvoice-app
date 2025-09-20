<?php
// app/Models/Invoice.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'amount',
        'status',
        'due_date',
        'invoice_number',
        'description',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'Paid';
    }

    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    public function isOverdue(): bool
    {
        return $this->isPending() && $this->due_date->isPast();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (!$invoice->invoice_number) {
                $year = date('Y');
                $lastInvoice = static::whereYear('created_at', $year)
                    ->orderBy('id', 'desc')
                    ->first();
                
                $nextNumber = $lastInvoice ? 
                    (int) substr($lastInvoice->invoice_number, -4) + 1 : 1;
                
                $invoice->invoice_number = 'INV-' . $year . '-' . 
                    str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
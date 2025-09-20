<?php
// app/Http/Requests/UpdateInvoiceRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'status' => 'required|in:Paid,Pending',
            'due_date' => 'required|date',
            'description' => 'nullable|string|max:1000',
        ];
    }
}

// =====================================
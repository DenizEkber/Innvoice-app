<?php
// app/Http/Requests/StoreInvoiceRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'due_date' => 'required|date|after_or_equal:today',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Please select a customer.',
            'customer_id.exists' => 'Selected customer does not exist.',
            'amount.required' => 'Invoice amount is required.',
            'amount.min' => 'Amount must be at least $0.01.',
            'amount.max' => 'Amount cannot exceed $999,999.99.',
            'due_date.after_or_equal' => 'Due date cannot be in the past.',
        ];
    }
}

// =====================================
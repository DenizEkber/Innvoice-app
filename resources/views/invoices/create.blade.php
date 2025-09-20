{{-- resources/views/invoices/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Create New Invoice</h5>
            </div>
            <form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="customer_id" class="form-label">Customer *</label>
                                <select class="form-control @error('customer_id') is-invalid @enderror" 
                                        id="customer_id" name="customer_id" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" 
                                                {{ old('customer_id', request('customer')) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} - {{ $customer->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="invoice_number" class="form-label">Invoice Number *</label>
                                <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" 
                                       id="invoice_number" name="invoice_number" 
                                       value="{{ old('invoice_number', 'INV-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)) }}" 
                                       required>
                                @error('invoice_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                       id="due_date" name="due_date" value="{{ old('due_date') }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-control @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Paid" {{ old('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Invoice Items -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6>Invoice Items</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem()">
                                <i class="fas fa-plus"></i> Add Item
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered" id="itemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th width="40%">Description</th>
                                        <th width="15%">Quantity</th>
                                        <th width="20%">Unit Price</th>
                                        <th width="20%">Total</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsTableBody">
                                    <tr class="item-row">
                                        <td>
                                            <input type="text" class="form-control" name="items[0][description]" 
                                                   value="{{ old('items.0.description') }}" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control quantity" name="items[0][quantity]" 
                                                   value="{{ old('items.0.quantity', 1) }}" min="1" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control unit-price" name="items[0][unit_price]" 
                                                   value="{{ old('items.0.unit_price', '0.00') }}" min="0" step="0.01" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-price" name="items[0][total]" 
                                                   value="{{ old('items.0.total', '0.00') }}" readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(this)" disabled>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                        <td>
                                            <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                                   id="amount" name="amount" value="{{ old('amount', '0.00') }}" 
                                                   min="0" step="0.01" readonly required>
                                            @error('amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Invoice
                    </button>
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let itemIndex = 1;

function addItem() {
    const tbody = document.getElementById('itemsTableBody');
    const newRow = `
        <tr class="item-row">
            <td>
                <input type="text" class="form-control" name="items[${itemIndex}][description]" required>
            </td>
            <td>
                <input type="number" class="form-control quantity" name="items[${itemIndex}][quantity]" value="1" min="1" required>
            </td>
            <td>
                <input type="number" class="form-control unit-price" name="items[${itemIndex}][unit_price]" value="0.00" min="0" step="0.01" required>
            </td>
            <td>
                <input type="number" class="form-control total-price" name="items[${itemIndex}][total]" value="0.00" readonly>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
    tbody.insertAdjacentHTML('beforeend', newRow);
    itemIndex++;
    updateRemoveButtons();
    attachEventListeners();
}

function removeItem(button) {
    button.closest('tr').remove();
    updateRemoveButtons();
    calculateTotal();
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.item-row');
    rows.forEach((row, index) => {
        const removeButton = row.querySelector('button');
        removeButton.disabled = rows.length === 1;
    });
}

function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        const itemTotal = quantity * unitPrice;
        row.querySelector('.total-price').value = itemTotal.toFixed(2);
        total += itemTotal;
    });
    document.getElementById('amount').value = total.toFixed(2);
}

function attachEventListeners() {
    document.querySelectorAll('.quantity, .unit-price').forEach(input => {
        input.removeEventListener('input', calculateTotal);
        input.addEventListener('input', calculateTotal);
    });
}

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    attachEventListeners();
    calculateTotal();
});
</script>
@endpush
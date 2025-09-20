{{-- resources/views/customers/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Customer Details')

@section('page-actions')
<div class="btn-group">
    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">
        <i class="fas fa-edit"></i> Edit Customer
    </a>
    <a href="{{ route('invoices.create', ['customer' => $customer->id]) }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Create Invoice
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-lg bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 80px; height: 80px;">
                    <span class="text-white fw-bold" style="font-size: 2rem;">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                </div>
                <h4 class="card-title">{{ $customer->name }}</h4>
                <p class="text-muted">{{ $customer->company_name ?: 'Individual Customer' }}</p>
                
                <div class="text-start mt-4">
                    <p><strong>Email:</strong> {{ $customer->email }}</p>
                    <p><strong>Phone:</strong> {{ $customer->phone ?: 'N/A' }}</p>
                    <p><strong>Address:</strong><br>{{ $customer->address ?: 'No address provided' }}</p>
                    <p><strong>Created:</strong> {{ $customer->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Customer Statistics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Total Invoices</h6>
                                <h3>{{ $customer->invoices_count ?? 0 }}</h3>
                            </div>
                            <i class="fas fa-file-invoice fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Total Revenue</h6>
                                <h3>${{ number_format($customer->invoices_sum_amount ?? 0, 2) }}</h3>
                            </div>
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Pending Amount</h6>
                                <h3>${{ number_format($pendingAmount ?? 0, 2) }}</h3>
                            </div>
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Invoices -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Customer Invoices</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice #</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>${{ number_format($invoice->amount, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $invoice->status == 'Paid' ? 'bg-success' : 'bg-warning' }}">
                                            {{ $invoice->status }}
                                        </span>
                                    </td>
                                    <td>{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</td>
                                    <td>{{ $invoice->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('invoices.show', $invoice) }}" 
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('invoices.edit', $invoice) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No invoices found for this customer</p>
                                        <a href="{{ route('invoices.create', ['customer' => $customer->id]) }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Create First Invoice
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
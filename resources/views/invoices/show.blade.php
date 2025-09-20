{{-- resources/views/invoices/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Invoice Details')

@section('page-actions')
<div class="btn-group">
    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-primary">
        <i class="fas fa-edit"></i> Edit Invoice
    </a>
    <button class="btn btn-info" onclick="window.print()">
        <i class="fas fa-print"></i> Print
    </button>
    @if($invoice->status != 'Paid')
        <form method="POST" action="{{ route('invoices.mark-paid', $invoice) }}" style="display: inline;">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-success" onclick="return confirm('Mark this invoice as paid?')">
                <i class="fas fa-check"></i> Mark as Paid
            </button>
        </form>
    @endif
</div>
@endsection

@section('content')
<div class="card" id="invoice-content">
    <div class="card-body">
        <!-- Invoice Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="text-primary">INVOICE</h2>
                <p class="mb-1"><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
                <p class="mb-1"><strong>Created:</strong> {{ $invoice->created_at->format('M d, Y') }}</p>
                @if($invoice->due_date)
                    <p class="mb-1"><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
                @endif
                <p class="mb-0">
                    <strong>Status:</strong> 
                    <span class="badge {{ $invoice->status == 'Paid' ? 'bg-success' : 'bg-warning' }}">
                        {{ $invoice->status }}
                    </span>
                </p>
            </div>
            <div class="col-md-6 text-end">
                <h4>{{ config('app.name', 'Invoice Manager') }}</h4>
                <p class="text-muted">Professional Invoice Management</p>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted">BILL TO:</h6>
                <p class="mb-1"><strong>{{ $invoice->customer->name }}</strong></p>
                @if($invoice->customer->company_name)
                    <p class="mb-1">{{ $invoice->customer->company_name }}</p>
                @endif
                <p class="mb-1">{{ $invoice->customer->email }}</p>
                @if($invoice->customer->phone)
                    <p class="mb-1">{{ $invoice->customer->phone }}</p>
                @endif
                @if($invoice->customer->address)
                    <p class="mb-0">{!! nl2br(e($invoice->customer->address)) !!}</p>
                @endif
            </div>
        </div>

        <!-- Invoice Description -->
        @if($invoice->description)
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="text-muted">DESCRIPTION:</h6>
                    <p>{{ $invoice->description }}</p>
                </div>
            </div>
        @endif

        <!-- Invoice Items -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Description</th>
                        <th width="15%" class="text-center">Quantity</th>
                        <th width="20%" class="text-end">Unit Price</th>
                        <th width="20%" class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoice->items ?? [] as $item)
                        <tr>
                            <td>{{ $item['description'] ?? 'Service' }}</td>
                            <td class="text-center">{{ $item['quantity'] ?? 1 }}</td>
                            <td class="text-end">${{ number_format($item['unit_price'] ?? 0, 2) }}</td>
                            <td class="text-end">${{ number_format($item['total'] ?? 0, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>Professional Services</td>
                            <td class="text-center">1</td>
                            <td class="text-end">${{ number_format($invoice->amount, 2) }}</td>
                            <td class="text-end">${{ number_format($invoice->amount, 2) }}</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                        <td class="text-end"><strong>${{ number_format($invoice->amount, 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Payment Information -->
        @if($invoice->status == 'Paid')
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                This invoice has been marked as paid.
            </div>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-clock me-2"></i>
                This invoice is pending payment.
                @if($invoice->due_date && $invoice->due_date->isPast())
                    <strong class="text-danger">This invoice is overdue.</strong>
                @endif
            </div>
        @endif
    </div>
</div>

<style media="print">
    .btn, .alert, nav, .sidebar { display: none !important; }
    .main-content { margin-left: 0 !important; }
    .card { border: none !important; box-shadow: none !important; }
</style>
@endsection
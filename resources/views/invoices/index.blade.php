{{-- resources/views/invoices/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Invoices')

@section('page-actions')
<a href="{{ route('invoices.create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Create Invoice
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0">Invoice List</h5>
            </div>
            <div class="col-auto">
                <form method="GET" class="d-flex">
                    <select name="status" class="form-select me-2">
                        <option value="">All Status</option>
                        <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                    <input type="text" name="search" class="form-control me-2" 
                           placeholder="Search invoices..." 
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td>
                                <strong>{{ $invoice->invoice_number }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <span class="text-white fw-bold">{{ strtoupper(substr($invoice->customer->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $invoice->customer->name }}</h6>
                                        <small class="text-muted">{{ $invoice->customer->company_name }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>${{ number_format($invoice->amount, 2) }}</strong>
                            </td>
                            <td>
                                <span class="badge {{ $invoice->status == 'Paid' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $invoice->status }}
                                </span>
                            </td>
                            <td>
                                {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}
                                @if($invoice->due_date && $invoice->due_date->isPast() && $invoice->status != 'Paid')
                                    <br><small class="text-danger">Overdue</small>
                                @endif
                            </td>
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
                                    @if($invoice->status != 'Paid')
                                        <form method="POST" action="{{ route('invoices.mark-paid', $invoice) }}" 
                                              style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success" 
                                                    onclick="return confirm('Mark this invoice as paid?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('invoices.destroy', $invoice) }}" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this invoice?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No invoices found</p>
                                <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Create First Invoice
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($invoices->hasPages())
        <div class="card-footer">
            {{ $invoices->links() }}
        </div>
    @endif
</div>
@endsection
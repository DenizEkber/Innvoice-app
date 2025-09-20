{{-- resources/views/customers/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Customers')

@section('page-actions')
<a href="{{ route('customers.create') }}" class="btn btn-primary">
    <i class="fas fa-plus"></i> Add Customer
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0">Customer List</h5>
            </div>
            <div class="col-auto">
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" 
                           placeholder="Search customers..." 
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Phone</th>
                        <th>Invoices</th>
                        <th>Total Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <span class="text-white fw-bold">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $customer->name }}</h6>
                                        <small class="text-muted">{{ $customer->created_at->format('M d, Y') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->company_name ?: 'N/A' }}</td>
                            <td>{{ $customer->phone ?: 'N/A' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $customer->invoices_count ?? 0 }}</span>
                            </td>
                            <td>${{ number_format($customer->invoices_sum_amount ?: 0, 2) }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('customers.show', $customer) }}" 
                                       class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('customers.destroy', $customer) }}" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this customer?')">
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
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No customers found</p>
                                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add First Customer
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($customers->hasPages())
        <div class="card-footer">
            {{ $customers->links() }}
        </div>
    @endif
</div>
@endsection
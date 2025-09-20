<?php
// routes/api.php (Optional API endpoints)

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Customer;
use App\Models\Invoice;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    
    // Customer API endpoints
    Route::get('/customers', function () {
        return Customer::with('invoices')->paginate(10);
    });
    
    Route::get('/customers/{customer}', function (Customer $customer) {
        return $customer->load('invoices');
    });
    
    // Invoice API endpoints
    Route::get('/invoices', function (Request $request) {
        $query = Invoice::with('customer');
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        
        return $query->paginate(10);
    });
    
    Route::get('/invoices/{invoice}', function (Invoice $invoice) {
        return $invoice->load('customer');
    });
    
    // Dashboard stats API
    Route::get('/dashboard-stats', function () {
        return [
            'total_customers' => Customer::count(),
            'total_invoices' => Invoice::count(),
            'paid_invoices' => Invoice::where('status', 'Paid')->count(),
            'pending_invoices' => Invoice::where('status', 'Pending')->count(),
            'total_revenue' => Invoice::where('status', 'Paid')->sum('amount'),
            'pending_amount' => Invoice::where('status', 'Pending')->sum('amount'),
            'recent_invoices' => Invoice::with('customer')->latest()->take(5)->get(),
        ];
    });
});

// =====================================
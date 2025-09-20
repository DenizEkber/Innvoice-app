<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers   = Customer::count();
        $totalInvoices    = Invoice::count();
        $paidInvoices     = Invoice::where('status', 'Paid')->count();
        $pendingInvoices  = Invoice::where('status', 'Pending')->count();
        $totalRevenue     = Invoice::where('status', 'Paid')->sum('amount');
        $pendingAmount    = Invoice::where('status', 'Pending')->sum('amount');

        $recentInvoices = Invoice::with('customer')
            ->latest()
            ->take(5)
            ->get();

        // Monthly invoice data for chart
        $monthlyData = Invoice::selectRaw('
            MONTH(created_at) as month,
            YEAR(created_at) as year,
            SUM(CASE WHEN status = "Paid" THEN amount ELSE 0 END) as paid,
            SUM(CASE WHEN status = "Pending" THEN amount ELSE 0 END) as pending
        ')
        ->whereYear('created_at', date('Y'))
        ->groupBy('year', 'month')
        ->orderBy('month')
        ->get();

        // Ay isimlerini oluştur (Jan, Feb, ...)
        $months = $monthlyData->pluck('month')->map(function ($month) {
            return date('M', mktime(0, 0, 0, $month, 1));
        });

        return view('dashboard', compact(
            'totalCustomers',
            'totalInvoices',
            'paidInvoices',
            'pendingInvoices',
            'totalRevenue',
            'pendingAmount',
            'recentInvoices',
            'monthlyData',
            'months'
        ));
    }
}

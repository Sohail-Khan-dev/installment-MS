<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\InstallmentPlan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::where('status', 'active')->count(),
            'total_products' => Product::count(),
            'products_in_stock' => Product::where('stock_quantity', '>', 0)->count(),
            'active_plans' => InstallmentPlan::where('status', 'active')->count(),
            'completed_plans' => InstallmentPlan::where('status', 'completed')->count(),
            'total_revenue' => Payment::where('status', 'paid')->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->sum('amount'),
        ];

        // Get recent activities
        $recent_plans = InstallmentPlan::with(['customer', 'product'])
            ->latest()
            ->limit(5)
            ->get();

        $recent_payments = Payment::with(['customer', 'installmentPlan'])
            ->latest()
            ->limit(5)
            ->get();

        // Get overdue payments
        $overdue_payments = Payment::with(['customer', 'installmentPlan'])
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->get();

        // Monthly revenue chart data
        $monthly_revenue = Payment::where('status', 'paid')
            ->where('payment_date', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw('MONTH(payment_date) as month'),
                DB::raw('YEAR(payment_date) as year'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('dashboard', compact(
            'stats',
            'recent_plans',
            'recent_payments',
            'overdue_payments',
            'monthly_revenue'
        ));
    }
}
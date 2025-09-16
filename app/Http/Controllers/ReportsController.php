<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\InstallmentPlan;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Display the main reports dashboard
     */
    public function index()
    {
        // Revenue statistics
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        $monthlyRevenue = Payment::where('status', 'paid')
            ->whereMonth('payment_date', Carbon::now()->month)
            ->whereYear('payment_date', Carbon::now()->year)
            ->sum('amount');
        
        // Overdue statistics
        $overduePayments = Payment::where('status', 'pending')
            ->where('due_date', '<', Carbon::now())
            ->count();
        $overdueAmount = Payment::where('status', 'pending')
            ->where('due_date', '<', Carbon::now())
            ->sum('amount');
        
        // Customer statistics
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::whereHas('installmentPlans', function($query) {
            $query->where('status', 'active');
        })->count();
        
        // Product statistics
        $topProducts = Product::select('products.*', DB::raw('COUNT(installment_plans.id) as plans_count'))
            ->leftJoin('installment_plans', 'products.id', '=', 'installment_plans.product_id')
            ->groupBy('products.id')
            ->orderBy('plans_count', 'desc')
            ->limit(5)
            ->get();
        
        // Monthly payment trend (last 6 months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenue = Payment::where('status', 'paid')
                ->whereMonth('payment_date', $month->month)
                ->whereYear('payment_date', $month->year)
                ->sum('amount');
            $monthlyTrend[] = [
                'month' => $month->format('M Y'),
                'revenue' => $revenue
            ];
        }
        
        return view('reports.index', compact(
            'totalRevenue',
            'monthlyRevenue',
            'overduePayments',
            'overdueAmount',
            'totalCustomers',
            'activeCustomers',
            'topProducts',
            'monthlyTrend'
        ));
    }
    
    /**
     * Display detailed revenue report
     */
    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());
        
        $payments = Payment::where('status', 'paid')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->with(['installmentPlan.customer', 'installmentPlan.product'])
            ->orderBy('payment_date', 'desc')
            ->paginate(20);
        
        $totalRevenue = Payment::where('status', 'paid')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');
        
        // Group by payment method
        $paymentMethods = Payment::where('status', 'paid')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->select('payment_method', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get();
        
        return view('reports.revenue', compact('payments', 'totalRevenue', 'paymentMethods', 'startDate', 'endDate'));
    }
    
    /**
     * Display overdue payments report
     */
    public function overdue()
    {
        $overduePayments = Payment::where('status', 'pending')
            ->where('due_date', '<', Carbon::now())
            ->with(['installmentPlan.customer', 'installmentPlan.product'])
            ->orderBy('due_date', 'asc')
            ->paginate(20);
        
        $totalOverdue = Payment::where('status', 'pending')
            ->where('due_date', '<', Carbon::now())
            ->sum('amount');
        
        // Group by days overdue
        $overdueByDays = [
            '1-7 days' => Payment::where('status', 'pending')
                ->whereBetween('due_date', [Carbon::now()->subDays(7), Carbon::now()->subDay()])
                ->count(),
            '8-30 days' => Payment::where('status', 'pending')
                ->whereBetween('due_date', [Carbon::now()->subDays(30), Carbon::now()->subDays(8)])
                ->count(),
            '31-60 days' => Payment::where('status', 'pending')
                ->whereBetween('due_date', [Carbon::now()->subDays(60), Carbon::now()->subDays(31)])
                ->count(),
            'Over 60 days' => Payment::where('status', 'pending')
                ->where('due_date', '<', Carbon::now()->subDays(60))
                ->count(),
        ];
        
        return view('reports.overdue', compact('overduePayments', 'totalOverdue', 'overdueByDays'));
    }
    
    /**
     * Display customer analytics report
     */
    public function customers()
    {
        // Top customers by total purchases
        $topCustomers = Customer::select('customers.*', 
                DB::raw('COUNT(DISTINCT installment_plans.id) as total_plans'),
                DB::raw('SUM(installment_plans.total_amount) as total_purchases')
            )
            ->leftJoin('installment_plans', 'customers.id', '=', 'installment_plans.customer_id')
            ->groupBy('customers.id')
            ->orderBy('total_purchases', 'desc')
            ->limit(10)
            ->get();
        
        // Customer payment behavior
        $paymentBehavior = Customer::select('customers.*',
                DB::raw('AVG(DATEDIFF(payments.payment_date, payments.due_date)) as avg_payment_delay'),
                DB::raw('COUNT(CASE WHEN payments.status = "paid" AND payments.payment_date <= payments.due_date THEN 1 END) as on_time_payments'),
                DB::raw('COUNT(CASE WHEN payments.status = "paid" AND payments.payment_date > payments.due_date THEN 1 END) as late_payments')
            )
            ->leftJoin('installment_plans', 'customers.id', '=', 'installment_plans.customer_id')
            ->leftJoin('payments', 'installment_plans.id', '=', 'payments.installment_plan_id')
            ->groupBy('customers.id')
            ->having(DB::raw('COUNT(payments.id)'), '>', 0)
            ->orderBy('on_time_payments', 'desc')
            ->get();
        
        // New customers trend
        $newCustomersTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = Customer::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
            $newCustomersTrend[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }
        
        return view('reports.customers', compact('topCustomers', 'paymentBehavior', 'newCustomersTrend'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(["customer", "installmentPlan.product"]);

        if ($request->has("status")) {
            $query->where("status", $request->status);
        }

        if ($request->has("overdue") && $request->overdue == "1") {
            $query->where("status", "pending")
                  ->where("due_date", "<", now());
        }

        $payments = $query->latest()->paginate(10);
        
        return view("payments.index", compact("payments"));
    }

    public function show(Payment $payment)
    {
        $payment->load(["customer", "installmentPlan.product"]);
        return view("payments.show", compact("payment"));
    }

    public function markPaid(Payment $payment)
    {
        $payment->markAsPaid();

        return redirect()->back()
            ->with("success", "Payment marked as paid successfully.");
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\InstallmentPlan;
use App\Models\Customer;
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

    public function create()
    {
        $installmentPlans = InstallmentPlan::with(['customer', 'product'])
            ->where('status', 'active')
            ->get();
        
        $customers = Customer::where('status', 'active')->get();
        
        return view('payments.create', compact('installmentPlans', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'installment_plan_id' => 'required|exists:installment_plans,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,card,cheque,online',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string'
        ]);

        // Get the installment plan
        $plan = InstallmentPlan::findOrFail($validated['installment_plan_id']);
        
        // Create the payment
        $payment = new Payment();
        $payment->installment_plan_id = $validated['installment_plan_id'];
        $payment->customer_id = $plan->customer_id;
        $payment->amount = $validated['amount'];
        $payment->payment_method = $validated['payment_method'];
        $payment->payment_date = $validated['payment_date'];
        $payment->reference_number = $validated['reference_number'] ?? null;
        $payment->notes = $validated['notes'] ?? null;
        $payment->status = 'paid';
        $payment->due_date = now(); // Set current date as due date for manual payments
        $payment->save();

        // Update installment plan if all payments are made
        $totalPaid = Payment::where('installment_plan_id', $plan->id)
            ->where('status', 'paid')
            ->sum('amount');
        
        if ($totalPaid >= $plan->total_amount) {
            $plan->status = 'completed';
            $plan->save();
        }

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load(["customer", "installmentPlan.product"]);
        return view("payments.show", compact("payment"));
    }

    public function edit(Payment $payment)
    {
        $payment->load(['customer', 'installmentPlan']);
        $installmentPlans = InstallmentPlan::with(['customer', 'product'])->get();
        
        return view('payments.edit', compact('payment', 'installmentPlans'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,card,cheque,online',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,paid,late,failed,refunded'
        ]);

        $payment->update($validated);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        
        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    public function markPaid(Payment $payment)
    {
        $payment->markAsPaid();

        return redirect()->back()
            ->with("success", "Payment marked as paid successfully.");
    }
}
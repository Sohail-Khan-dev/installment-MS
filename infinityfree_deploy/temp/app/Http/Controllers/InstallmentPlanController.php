<?php

namespace App\Http\Controllers;

use App\Models\InstallmentPlan;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InstallmentPlanController extends Controller
{
    public function index()
    {
        $plans = InstallmentPlan::with(["customer", "product"])
            ->latest()
            ->paginate(10);
        return view("installment-plans.index", compact("plans"));
    }

    public function create()
    {
        $customers = Customer::where("status", "active")->get();
        $products = Product::where("is_active", true)->where("stock_quantity", ">", 0)->get();
        return view("installment-plans.create", compact("customers", "products"));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "customer_id" => "required|exists:customers,id",
            "product_id" => "required|exists:products,id",
            "down_payment" => "required|numeric|min:0",
            "number_of_installments" => "required|integer|min:1",
            "interest_rate" => "nullable|numeric|min:0",
            "payment_frequency" => "required|in:monthly,weekly,bi-weekly,quarterly",
            "start_date" => "required|date",
        ]);

        $product = Product::find($validated["product_id"]);
        $validated["total_amount"] = $product->price;

        $plan = InstallmentPlan::create($validated);

        // Decrease product stock
        $product->decrementStock();

        // Create payment schedule
        $this->createPaymentSchedule($plan);

        return redirect()->route("installment-plans.index")
            ->with("success", "Installment plan created successfully.");
    }

    public function show(InstallmentPlan $installmentPlan)
    {
        $installmentPlan->load(["customer", "product", "payments", "invoices"]);
        return view("installment-plans.show", compact("installmentPlan"));
    }

    public function recordPayment(Request $request, InstallmentPlan $installmentPlan)
    {
        $validated = $request->validate([
            "amount" => "required|numeric|min:0",
            "payment_method" => "required|in:cash,bank_transfer,card,cheque,online",
            "reference_number" => "nullable|string",
        ]);

        $payment = Payment::create([
            "installment_plan_id" => $installmentPlan->id,
            "customer_id" => $installmentPlan->customer_id,
            "amount" => $validated["amount"],
            "payment_date" => now(),
            "due_date" => $installmentPlan->next_payment_date,
            "payment_method" => $validated["payment_method"],
            "reference_number" => $validated["reference_number"] ?? null,
            "status" => "paid",
        ]);

        return redirect()->route("installment-plans.show", $installmentPlan)
            ->with("success", "Payment recorded successfully.");
    }

    private function createPaymentSchedule(InstallmentPlan $plan)
    {
        $dueDate = Carbon::parse($plan->start_date);
        
        for ($i = 1; $i <= $plan->number_of_installments; $i++) {
            Payment::create([
                "installment_plan_id" => $plan->id,
                "customer_id" => $plan->customer_id,
                "amount" => $plan->installment_amount,
                "due_date" => $dueDate->copy(),
                "status" => "pending",
            ]);

            // Move to next due date based on frequency
            switch ($plan->payment_frequency) {
                case "weekly":
                    $dueDate->addWeek();
                    break;
                case "bi-weekly":
                    $dueDate->addWeeks(2);
                    break;
                case "monthly":
                    $dueDate->addMonth();
                    break;
                case "quarterly":
                    $dueDate->addMonths(3);
                    break;
            }
        }
    }
}
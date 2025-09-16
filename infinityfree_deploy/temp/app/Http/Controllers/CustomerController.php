<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view("customers.index", compact("customers"));
    }

    public function create()
    {
        return view("customers.create");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "first_name" => "required|string|max:100",
            "last_name" => "required|string|max:100",
            "email" => "required|email|unique:customers",
            "phone" => "required|string|max:20",
            "address" => "nullable|string",
            "city" => "nullable|string|max:100",
            "cnic" => "nullable|string|unique:customers",
            "credit_limit" => "nullable|numeric|min:0",
        ]);

        Customer::create($validated);

        return redirect()->route("customers.index")
            ->with("success", "Customer created successfully.");
    }

    public function show(Customer $customer)
    {
        $customer->load(["installmentPlans.product", "payments"]);
        return view("customers.show", compact("customer"));
    }

    public function edit(Customer $customer)
    {
        return view("customers.edit", compact("customer"));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            "first_name" => "required|string|max:100",
            "last_name" => "required|string|max:100",
            "email" => "required|email|unique:customers,email," . $customer->id,
            "phone" => "required|string|max:20",
            "address" => "nullable|string",
            "city" => "nullable|string|max:100",
            "cnic" => "nullable|string|unique:customers,cnic," . $customer->id,
            "credit_limit" => "nullable|numeric|min:0",
            "status" => "required|in:active,inactive,blocked",
        ]);

        $customer->update($validated);

        return redirect()->route("customers.index")
            ->with("success", "Customer updated successfully.");
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route("customers.index")
            ->with("success", "Customer deleted successfully.");
    }
}
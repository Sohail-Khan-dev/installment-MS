<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\InstallmentPlan;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create products
        $products = [
            ['name' => 'Samsung Galaxy S23', 'category' => 'Mobile Phones', 'price' => 120000, 'stock_quantity' => 50, 'sku' => 'SGS23'],
            ['name' => 'iPhone 14 Pro', 'category' => 'Mobile Phones', 'price' => 150000, 'stock_quantity' => 30, 'sku' => 'IP14P'],
            ['name' => 'Dell Laptop XPS 13', 'category' => 'Laptops', 'price' => 180000, 'stock_quantity' => 20, 'sku' => 'DLXPS13'],
            ['name' => 'HP Pavilion 15', 'category' => 'Laptops', 'price' => 95000, 'stock_quantity' => 40, 'sku' => 'HPP15'],
            ['name' => 'Sony LED TV 55"', 'category' => 'Electronics', 'price' => 85000, 'stock_quantity' => 25, 'sku' => 'SLED55'],
            ['name' => 'LG Refrigerator', 'category' => 'Appliances', 'price' => 75000, 'stock_quantity' => 15, 'sku' => 'LGREF'],
            ['name' => 'Haier AC 1.5 Ton', 'category' => 'Appliances', 'price' => 65000, 'stock_quantity' => 30, 'sku' => 'HAC15'],
            ['name' => 'Honda CD 70', 'category' => 'Motorcycles', 'price' => 95000, 'stock_quantity' => 10, 'sku' => 'HCD70'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Create customers
        $customers = [
            [
                'first_name' => 'Ahmed',
                'last_name' => 'Khan',
                'email' => 'ahmed@example.com',
                'phone' => '03001234567',
                'address' => 'House 123, Street 5',
                'city' => 'Karachi',
                'cnic' => '4210112345678',
                'credit_limit' => 200000,
            ],
            [
                'first_name' => 'Fatima',
                'last_name' => 'Ali',
                'email' => 'fatima@example.com',
                'phone' => '03111234567',
                'address' => 'House 456, Block B',
                'city' => 'Lahore',
                'cnic' => '4210187654321',
                'credit_limit' => 150000,
            ],
            [
                'first_name' => 'Muhammad',
                'last_name' => 'Hassan',
                'email' => 'hassan@example.com',
                'phone' => '03211234567',
                'address' => 'Flat 789, Tower C',
                'city' => 'Islamabad',
                'cnic' => '4210198765432',
                'credit_limit' => 300000,
            ],
            [
                'first_name' => 'Ayesha',
                'last_name' => 'Malik',
                'email' => 'ayesha@example.com',
                'phone' => '03331234567',
                'address' => 'House 321, Phase 2',
                'city' => 'Peshawar',
                'cnic' => '4210176543210',
                'credit_limit' => 100000,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        // Create sample installment plans
        $plan1 = InstallmentPlan::create([
            'customer_id' => 1,
            'product_id' => 1,
            'total_amount' => 120000,
            'down_payment' => 20000,
            'number_of_installments' => 12,
            'interest_rate' => 5,
            'start_date' => Carbon::now()->subMonths(3),
            'payment_frequency' => 'monthly',
            'status' => 'active',
        ]);

        $plan2 = InstallmentPlan::create([
            'customer_id' => 2,
            'product_id' => 3,
            'total_amount' => 180000,
            'down_payment' => 30000,
            'number_of_installments' => 18,
            'interest_rate' => 7,
            'start_date' => Carbon::now()->subMonths(2),
            'payment_frequency' => 'monthly',
            'status' => 'active',
        ]);

        // Create sample payments for the first plan
        for ($i = 1; $i <= 3; $i++) {
            Payment::create([
                'installment_plan_id' => $plan1->id,
                'customer_id' => 1,
                'amount' => $plan1->installment_amount,
                'payment_date' => Carbon::now()->subMonths(4 - $i),
                'due_date' => Carbon::now()->subMonths(4 - $i),
                'payment_method' => 'cash',
                'status' => 'paid',
            ]);
        }

        // Create pending payment
        Payment::create([
            'installment_plan_id' => $plan1->id,
            'customer_id' => 1,
            'amount' => $plan1->installment_amount,
            'payment_date' => Carbon::now()->addDays(5),
            'due_date' => Carbon::now()->addDays(5),
            'status' => 'pending',
        ]);
    }
}
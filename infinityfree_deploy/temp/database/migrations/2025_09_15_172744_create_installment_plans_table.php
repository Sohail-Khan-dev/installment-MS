<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('installment_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('down_payment', 10, 2)->default(0);
            $table->decimal('financed_amount', 10, 2);
            $table->integer('number_of_installments');
            $table->decimal('installment_amount', 10, 2);
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('payment_frequency', ['monthly', 'weekly', 'bi-weekly', 'quarterly'])->default('monthly');
            $table->enum('status', ['active', 'completed', 'cancelled', 'defaulted'])->default('active');
            $table->decimal('total_paid', 10, 2)->default(0);
            $table->decimal('balance', 10, 2);
            $table->integer('payments_made')->default(0);
            $table->date('next_payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_plans');
    }
};
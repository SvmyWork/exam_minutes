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
        Schema::create('billings', function (Blueprint $table) {
            $table->id('billing_id'); // Primary key
            $table->unsignedBigInteger('user_id'); // FK to users/teachers table
            $table->string('user_type')->default('teacher'); // teacher, admin, staff, etc.
            $table->string('email_id');
            $table->string('billing_period'); // e.g. 2025-08, 2025-Q3
            $table->date('billing_date');
            $table->decimal('billing_amount', 10, 2);
            $table->string('payment_gateway')->nullable(); // Stripe, PayPal, Razorpay
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->timestamps();

            // Foreign key (optional: depends on your "users" table)
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};

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
        Schema::create('job_requests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // Relationships
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('worker_id')->nullable();
            // Job request number
            $table->string('job_number')->unique();
            // Contact information
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            // Address information
            $table->string('api_search')->nullable(); // API search result
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('suburb')->nullable();
            $table->string('area')->nullable();
            $table->string('street_address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('location')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            // Job details
            $table->string('job_type');
            $table->string('urgency_level');
            $table->integer('job_budget')->nullable();
            $table->text('job_description');
            // Financial information
            $table->string('payment_method')->nullable(); // Credit Card, PayPal, etc.
            $table->string('payment_status')->default('Pending'); // Pending, Completed, Failed
            $table->string('transaction_id')->nullable(); // Unique transaction ID for payment
            $table->string('invoice_number')->nullable(); // Invoice number for the job
            $table->integer('payment_amount')->nullable(); // Amount paid so far
            $table->string('payment_date')->nullable(); // Date of payment
            $table->string('payment_receipt')->nullable(); // Receipt for the payment
            $table->integer('full_amount')->nullable(); // Total amount for the job
            // Internal Use
            $table->dateTime('completion_date')->nullable(); // Date when the job was completed
            $table->string('status')->default('Pending'); // Pending, In Progress, Completed, Cancelled
            $table->string('notes')->nullable(); // Internal notes for the job request
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_requests');
    }
};

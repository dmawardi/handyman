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
            $table->string('street_address')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->string('location')->nullable();
            // Job details
            $table->string('job_type');
            $table->string('urgency_level');
            $table->integer('job_budget')->nullable();
            $table->string('job_description');
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

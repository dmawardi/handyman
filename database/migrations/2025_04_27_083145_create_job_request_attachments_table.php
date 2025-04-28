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
        Schema::create('job_request_attachments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // Foreign key to job_requests table
            $table->foreignId('job_request_id')->constrained()->onDelete('cascade');
            // Foreign key to users table
            $table->foreignId('user_id')->nullable();
            // Image fields
            $table->string('path'); // Path to the stored image
            $table->string('original_filename')->nullable(); // Original filename
            $table->string('file_type'); // File MIME type
            $table->integer('file_size'); // File size in bytes
            $table->string('type')->default('user_upload'); // Options: user_upload, admin_upload, internal,
            $table->string('caption')->nullable(); // Optional caption/description
            $table->boolean('is_visible_to_customer')->default(true); // Whether customer can see it
            $table->boolean('is_active')->default(true); // Soft delete flag
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_request_attachments');
    }
};

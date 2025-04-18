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
        Schema::create('job_updates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // Relationships
            $table->foreignId('job_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained(); // User who made the update
            // Fields for job updates
            $table->string('update_type'); // Type of update (e.g., status change, note)
            $table->string('update_description'); // Description of the update
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_updates');
    }
};

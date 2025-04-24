<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRequest extends Model
{
    /** @use HasFactory<\Database\Factories\JobRequestFactory> */
    use HasFactory;

    protected $guarded = [];

    // Relationships
    public function requestor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // The 'worker' method indicates that a job request can be assigned to a worker
    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function images()
    {
        return $this->hasMany(JobRequestImage::class);
    }

    public function noteUpdates()
    {
        return $this->hasMany(JobUpdate::class);
    }

    // Lifecycle Hooks
    // This method is called after the model is instantiated
    protected static function booted()
    {
        // During the creation of an order, generate a unique order number
        static::creating(function ($job) {
            $job->job_number = self::generateOrderNumber();
        });
    }

    // Helper Methods
    /**
     * Generate a unique job request number.
     *
     * @return string
     */
    // This method generates a unique job request number using the current timestamp and a random number
    private static function generateOrderNumber()
    {
        // Use the current timestamp and a random number to ensure uniqueness
        $timestamp = now()->format('YmdHis'); // Format: YYYYMMDDHHMMSS
        $randomNumber = mt_rand(1000, 9999); // Generate a random 4-digit number
    
        return 'JOB-' . $timestamp . '-' . $randomNumber; // Example: ORD-20250407123045-1234
    }

    protected $casts = [
        'completion_date' => 'datetime', // ✅ Correct
    ];
}

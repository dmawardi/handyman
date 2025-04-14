<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobUpdate extends Model
{
    /** @use HasFactory<\Database\Factories\JobUpdateFactory> */
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the job request that owns the job update.
     */
    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class);
    }
    /**
     * Get the user who made the update.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

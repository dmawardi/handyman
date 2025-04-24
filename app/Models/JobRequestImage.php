<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class JobRequestImage extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    protected $casts = [
        'is_visible_to_customer' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSrc()
    {
        // Return the URL of the image using AWS s3
        return \Illuminate\Support\Facades\Storage::disk('s3')->url($this->path);
    }
}

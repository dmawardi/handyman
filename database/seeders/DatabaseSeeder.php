<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create 5 job requests for the test user
        \App\Models\JobRequest::factory(5)->create([
            'user_id' => $user->id, // Assuming the test user has ID 1
        ]);
        // Create 5 job updates for each job request
        \App\Models\JobRequest::all()->each(function ($jobRequest, $user) {
            // Create 5 job request images for each job request
            \App\Models\JobRequestAttachment::factory(2)->create([
                'job_request_id' => $jobRequest->id,
                'user_id' => $user, // Assuming the test user has ID 1
            ]);
            // Create 5 job updates for each job request
            \App\Models\JobUpdate::factory(5)->create([
                'job_request_id' => $jobRequest->id,
            ]);

        });
    }
}

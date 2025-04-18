<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobUpdate>
 */
class JobUpdateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_request_id' => \App\Models\JobRequest::factory(),
            'update_type' => $this->faker->randomElement(['status_change', 'note']),
            'update_description' => $this->faker->sentence(),
        ];
    }
    /**
     * Assign a specific job request to the created update.
     *
     * @return static
     */
    public function forJobRequest($jobRequestId): static
    {
        return $this->state(function (array $attributes) use ($jobRequestId) {
            return [
                'job_request_id' => $jobRequestId,
            ];
        });
    }
}

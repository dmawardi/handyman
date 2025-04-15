<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobRequest>
 */
class JobRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Relationships
            'user_id' => User::factory(),
            // Contact information
            'contact_name' => $this->faker->name(),
            'contact_email' => $this->faker->unique()->safeEmail(),
            'contact_phone' => $this->faker->phoneNumber(),
            // Address information
            'street_address' => $this->faker->streetAddress(),
            'state' => $this->faker->state(),
            'zip_code' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'location' => $this->faker->city(),
            'job_type' => $this->faker->randomElement([
                'Plumbing',
                'Electrical',
                'Painting', 
                'Appliance Repair',
                'Outdoor/Garden',
                'Installations',
                'Cleaning/Maintenance',
                'Other'
            ]),
            'urgency_level' => $this->faker->randomElement([
                'Low - Within 2 weeks',
                'Medium - Within 1 week',
                'High - Within 48 hours',
                'Emergency - Same day'
            ]),
            'job_budget' => $this->faker->numberBetween(100, 10000),
            'job_description' => $this->faker->paragraph(),
        ];
    }
    /**
     * Assign a specific user to the created job request.
     *
     * @return static
     */
    public function forUser($userId): static
    {
        return $this->state(function (array $attributes) use ($userId) {
            return [
                'user_id' => $userId,
            ];
        });
    }
}

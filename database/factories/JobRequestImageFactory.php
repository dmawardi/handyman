<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobRequestImage>
 */
class JobRequestImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => $this->faker->imageUrl(),
            'original_filename' => $this->faker->word . '.jpg',
            'file_type' => 'image/jpeg',
            'file_size' => $this->faker->numberBetween(1000, 100000),
            'image_type' => $this->faker->randomElement(['user_upload', 'admin_upload', 'internal', 'before', 'after']),
            'caption' => $this->faker->sentence,
            'is_visible_to_customer' => $this->faker->boolean,
            'is_active' => $this->faker->boolean,
            'user_id' => \App\Models\User::factory(), // Assuming you have a User factory
            'job_request_id' => \App\Models\JobRequest::factory(), // Assuming you have a JobRequest factory
        ];
    }
    /**
     * Indicate that the model's image is not visible to the customer.
     *
     * @return static
     */
    public function notVisibleToCustomer(): static
    {
        return $this->state([
            'is_visible_to_customer' => false,
        ]);
    }
    /**
     * Indicate that the model's image is not active.
     *
     * @return static
     */
    public function notActive(): static
    {
        return $this->state([
            'is_active' => false,
        ]);
    }
    /**
     * Indicate that the image belongs to a particular Job Request.
     *
     * @return static
     */
    public function forJobRequest($jobRequestId): static
    {
        return $this->state([
            'job_request_id' => $jobRequestId,
        ]);
    }
    /**
     * Indicate that the image belongs to a particular user.
     *
     * @return static
     */
    public function forUser($userId): static
    {
        return $this->state([
            'user_id' => $userId,
        ]);
    }
}

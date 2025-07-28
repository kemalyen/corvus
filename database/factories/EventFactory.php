<?php

namespace Database\Factories;

use App\Enums\EventStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'start_time' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'location' => $this->faker->address,
            'organizer' => $this->faker->name,
            'capacity' => $this->faker->numberBetween(10, 100),
            'is_public' => $this->faker->boolean(80), // 80%
            'status' => fake()->randomElement(EventStatus::cases())->value,
            'organizer_id' => \App\Models\User::factory(), // Assuming organizer is a User
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

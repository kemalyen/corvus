<?php

namespace Database\Factories;

use App\Enums\RegistrationStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registration>
 */
class EventRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => \App\Models\Event::factory(),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'is_attending' => $this->faker->boolean,
            'registered_at' => now(),
            'status' => fake()->randomElement(RegistrationStatus::cases())->value,
            'notes' => $this->faker->optional()->text,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(5),
            'description' => $this->faker->paragraph(3),
            'location' => $this->faker->city(),
            'event_date' => $this->faker->dateTimeBetween('+1 day', '+30 days'),
            'event_end_date' => $this->faker->dateTimeBetween('+31 days', '+60 days'),
            'event_url' => $this->faker->url(),
            'is_active' => $this->faker->boolean(80),
            'category' => $this->faker->randomElement(['conference', 'workshop', 'webinar', 'meetup']),
            'status' => $this->faker->randomElement(['scheduled', 'ongoing', 'completed', 'cancelled']),
            'capacity' => $this->faker->numberBetween(50, 500),
            'registered' => $this->faker->numberBetween(0, 100),
        ];
    }
}

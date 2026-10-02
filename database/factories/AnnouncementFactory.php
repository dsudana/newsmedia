<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(6),
            'content' => $this->faker->paragraph(4),
            'category' => $this->faker->randomElement(['general', 'system', 'event', 'promotion']),
            'priority' => $this->faker->randomElement([1, 2, 3]),
            'starts_at' => $this->faker->dateTime(),
            'ends_at' => $this->faker->dateTimeBetween('+1 day', '+30 days'),
            'is_pinned' => $this->faker->boolean(20),
            'is_active' => $this->faker->boolean(85),
        ];
    }
}

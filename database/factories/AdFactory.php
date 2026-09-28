<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ad>
 */
class AdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'type' => fake()->randomElement(['banner', 'script', 'adsense']),
            'placement' => fake()->randomElement(['header', 'sidebar', 'in_article', 'footer']),
            'image' => null,
            'url' => fake()->url(),
            'script' => null,
            'start_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'end_date' => fake()->dateTimeBetween('now', '+1 month'),
            'is_active' => true,
        ];
    }
}

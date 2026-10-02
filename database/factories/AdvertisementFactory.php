<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AdvertisementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'type' => $this->faker->randomElement(['banner', 'adsense', 'custom']),
            'placement' => $this->faker->randomElement(['header', 'sidebar', 'footer']),
            'is_active' => $this->faker->boolean(75),
        ];
    }
}

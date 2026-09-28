<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AffiliateLink>
 */
class AffiliateLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'destination_url' => fake()->url(),
            'slug' => fake()->slug(),
            'commission_type' => 'fixed',
            'commission_value' => 0,
            'clicks_count' => 0,
        ];
    }
}

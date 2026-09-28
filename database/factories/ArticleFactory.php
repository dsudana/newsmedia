<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'slug' => fake()->slug(),
            'content' => fake()->paragraphs(3, true),
            'excerpt' => fake()->paragraph(),
            'featured_image' => null, // Or use faker image url if needed
            'meta_title' => fake()->sentence(),
            'meta_description' => fake()->sentence(),
            'status' => fake()->randomElement(['published', 'draft', 'archived']),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'user_id' => \App\Models\User::factory(),
            'category_id' => \App\Models\Category::factory(),
        ];
    }
}

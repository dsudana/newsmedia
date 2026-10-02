<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'article_id' => Article::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'content' => $this->faker->paragraph(2),
            'is_approved' => $this->faker->boolean(70),
            'parent_id' => null,
        ];
    }

    public function withReply()
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => fn() => \App\Models\Comment::inRandomOrder()->first()?->id,
            ];
        });
    }
}

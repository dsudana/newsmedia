<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Breaking News', 'slug' => 'breaking-news'],
            ['name' => 'Technology', 'slug' => 'technology'],
            ['name' => 'Business', 'slug' => 'business'],
            ['name' => 'Politics', 'slug' => 'politics'],
            ['name' => 'Sports', 'slug' => 'sports'],
            ['name' => 'Entertainment', 'slug' => 'entertainment'],
            ['name' => 'Lifestyle', 'slug' => 'lifestyle'],
            ['name' => 'Health', 'slug' => 'health'],
            ['name' => 'Environment', 'slug' => 'environment'],
            ['name' => 'Education', 'slug' => 'education'],
            ['name' => 'Travel', 'slug' => 'travel'],
            ['name' => 'Food & Drink', 'slug' => 'food-drink'],
        ];

        // Create tags
        foreach ($tags as $tag) {
            \App\Models\Tag::firstOrCreate($tag);
        }

        // Associate random tags with published articles
        $articles = \App\Models\Article::where('status', 'published')->inRandomOrder()->limit(20)->get();

        foreach ($articles as $article) {
            $randomTags = \App\Models\Tag::inRandomOrder()->limit(rand(2, 4))->pluck('id')->toArray();
            $article->tags()->sync($randomTags, false);
        }
    }
}

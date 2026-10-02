<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SeoSettingFactory extends Factory
{
    public function definition(): array
    {
        $pageNames = ['home', 'blog', 'about', 'contact', 'categories', 'articles', 'dashboard', 'admin'];
        $pageName = $this->faker->randomElement($pageNames);

        return [
            'page_name' => $pageName,
            'page_title' => $this->faker->sentence(4),
            'meta_title' => $this->faker->sentence(5),
            'meta_description' => $this->faker->sentence(10),
            'meta_keywords' => implode(', ', $this->faker->words(5)),
            'canonical_url' => $this->faker->url(),
            'og_title' => $this->faker->sentence(4),
            'og_description' => $this->faker->paragraph(1),
            'og_image' => $this->faker->imageUrl(1200, 630),
            'og_type' => 'website',
            'twitter_title' => $this->faker->sentence(4),
            'twitter_description' => $this->faker->paragraph(1),
            'twitter_image' => $this->faker->imageUrl(1200, 630),
            'twitter_card' => 'summary_large_image',
            'structured_data' => [
                'type' => 'WebPage',
                'headline' => $this->faker->sentence(5),
                'description' => $this->faker->paragraph(1),
            ],
            'index' => $this->faker->boolean(90),
            'follow' => $this->faker->boolean(90),
            'sitemap_priority' => $this->faker->randomFloat(1, 0.1, 1.0),
            'sitemap_changefreq' => $this->faker->randomElement(['daily', 'weekly', 'monthly']),
            'is_active' => $this->faker->boolean(85),
        ];
    }
}

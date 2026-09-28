<?php

namespace Database\Seeders;

use App\Models\HomePageSetting;
use Illuminate\Database\Seeder;

class HomePageSettingSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            ['section_name' => 'hero', 'is_enabled' => true, 'order' => 1, 'items_count' => 5],
            ['section_name' => 'category_strip', 'is_enabled' => true, 'order' => 2, 'items_count' => 6],
            ['section_name' => 'recent_articles', 'is_enabled' => true, 'order' => 3, 'items_count' => 6],
            ['section_name' => 'popular_articles', 'is_enabled' => true, 'order' => 4, 'items_count' => 5],
            ['section_name' => 'featured_category', 'is_enabled' => true, 'order' => 5, 'items_count' => 4],
            ['section_name' => 'sidebar', 'is_enabled' => true, 'order' => 6, 'items_count' => 5],
        ];

        foreach ($sections as $section) {
            HomePageSetting::create($section);
        }
    }
}

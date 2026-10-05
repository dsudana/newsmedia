<?php

namespace Database\Seeders;

use App\Models\AdSlot;
use Illuminate\Database\Seeder;

class AdSlotSeeder extends Seeder
{
    public function run(): void
    {
        $adSlots = [
            [
                'name' => 'Header Banner',
                'slug' => 'header_banner',
                'location' => 'header',
                'ad_type' => 'banner',
                'is_active' => true,
            ],
            [
                'name' => 'Sidebar Top',
                'slug' => 'sidebar_top',
                'location' => 'sidebar',
                'ad_type' => 'banner',
                'is_active' => true,
            ],
            [
                'name' => 'Sidebar Bottom',
                'slug' => 'sidebar_bottom',
                'location' => 'sidebar',
                'ad_type' => 'banner',
                'is_active' => true,
            ],
            [
                'name' => 'Content Middle',
                'slug' => 'content_middle',
                'location' => 'content',
                'ad_type' => 'banner',
                'is_active' => true,
            ],
            [
                'name' => 'Footer Banner',
                'slug' => 'footer_banner',
                'location' => 'footer',
                'ad_type' => 'banner',
                'is_active' => true,
            ],
            [
                'name' => 'Above Comments',
                'slug' => 'above_comments',
                'location' => 'article',
                'ad_type' => 'banner',
                'is_active' => true,
            ],
        ];

        foreach ($adSlots as $slot) {
            AdSlot::updateOrCreate(
                ['slug' => $slot['slug']],
                $slot
            );
        }

        $this->command->info('✅ Ad slots created successfully!');
    }
}

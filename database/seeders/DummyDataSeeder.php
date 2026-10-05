<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\Event;
use App\Models\Announcement;
use App\Models\Comment;
use App\Models\Advertisement;
use App\Models\SeoSetting;
use App\Models\AffiliateLink;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating dummy data...');

        // Create Events (10)
        $this->command->line('Creating Events...');
        Event::factory(10)->create();

        // Create Announcements (8)
        $this->command->line('Creating Announcements...');
        Announcement::factory(8)->create();

        // Create Comments (20)
        $this->command->line('Creating Comments...');
        Comment::factory(20)->create();

        // Create Advertisements (12) with proper placement and dimensions
        $this->command->line('Creating Advertisements...');
        Advertisement::factory(12)->create();

        // Create Ads (10) for different placements
        $this->command->line('Creating Ads...');
        Ad::factory(10)->create();

        // Create SEO Settings (5 for different pages)
        $this->command->line('Creating SEO Settings...');
        $pageNames = ['home', 'blog', 'about', 'contact', 'categories'];
        foreach ($pageNames as $pageName) {
            SeoSetting::updateOrCreate(
                ['page_name' => $pageName],
                SeoSetting::factory()->make(['page_name' => $pageName])->toArray()
            );
        }

        // Create Affiliate Links (15)
        $this->command->line('Creating Affiliate Links...');
        AffiliateLink::factory(15)->create();

        $this->command->info('✅ Dummy data created successfully!');
        $this->command->info('Summary:');
        $this->command->info('  - 10 Events');
        $this->command->info('  - 8 Announcements');
        $this->command->info('  - 20 Comments');
        $this->command->info('  - 12 Advertisements (with dummy images)');
        $this->command->info('  - 10 Ads (with dummy images)');
        $this->command->info('  - 5 SEO Settings');
        $this->command->info('  - 15 Affiliate Links');
    }
}

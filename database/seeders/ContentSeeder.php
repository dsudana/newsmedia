<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Specific Categories
        $categories = [
            ['name' => 'Politik', 'slug' => 'politik', 'description' => 'Berita politik dan analisis terkini.', 'icon' => '🏛️', 'order' => 1, 'is_active' => true],
            ['name' => 'Business', 'slug' => 'business', 'description' => 'Ekonomi global, pasar, dan tren bisnis.', 'icon' => '💼', 'order' => 2, 'is_active' => true],
            ['name' => 'Technology', 'slug' => 'technology', 'description' => 'Berita teknologi, review, dan terobosan.', 'icon' => '💻', 'order' => 3, 'is_active' => true],
            ['name' => 'Entertainment', 'slug' => 'entertainment', 'description' => 'Film, musik, dan berita selebriti.', 'icon' => '🎬', 'order' => 4, 'is_active' => true],
            ['name' => 'Health', 'slug' => 'health', 'description' => 'Tips kesehatan, riset medis, dan wellness.', 'icon' => '⚕️', 'order' => 5, 'is_active' => true],
            ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Skor langsung, pertandingan, dan komentar olahraga.', 'icon' => '⚽', 'order' => 6, 'is_active' => true],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::firstOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        // 2. Create Tags
        $tags = ['Breaking News', 'Featured', 'Trending', 'Opinion', 'Analysis', 'Global', 'Local'];
        foreach ($tags as $tagName) {
            \App\Models\Tag::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($tagName)],
                ['name' => $tagName]
            );
        }

        // 3. Get Users
        $users = \App\Models\User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['editor', 'writer', 'admin']);
        })->get();

        if ($users->isEmpty()) {
            $users = \App\Models\User::factory(3)->create();
        }

        // 4. Create Articles
        $allCategories = \App\Models\Category::all();
        $allTags = \App\Models\Tag::all();

        $allCategories->each(function ($category) use ($users, $allTags) {
            // Create 5-8 articles per category
            \App\Models\Article::factory(rand(5, 8))->create([
                'category_id' => $category->id,
                'user_id' => $users->random()->id,
                'status' => 'published',
                'featured_image' => null,
            ])->each(function ($article) use ($allTags) {
                // Attach 1-3 random tags
                $article->tags()->attach($allTags->random(rand(1, 3)));

                // Create ArticleMeta for each article
                \App\Models\ArticleMeta::create([
                    'article_id' => $article->id,
                    'meta_title' => substr($article->title, 0, 60),
                    'meta_description' => substr($article->excerpt ?? $article->content, 0, 160),
                    'focus_keyword' => explode(' ', $article->title)[0],
                    'keywords_used' => array_slice(explode(' ', $article->title), 0, 3),
                ]);
            });
        });

        // 5. Create Ads
        \App\Models\Ad::create([
            'name' => 'Demo Home Banner',
            'type' => 'script',
            'placement' => 'header',
            'script' => '<div class="p-4 bg-gray-200 text-center text-gray-500 border border-dashed border-gray-300"><strong>728x90 Ad Banner Placeholder</strong><br>Put your AdSense code here</div>',
            'is_active' => true,
        ]);

        \App\Models\Ad::create([
            'name' => 'Demo Sidebar Ad',
            'type' => 'script',
            'placement' => 'sidebar',
            'script' => '<div class="p-4 bg-gray-200 text-center text-gray-500 border border-dashed border-gray-300 h-64 flex items-center justify-center"><strong>300x250 Ad</strong></div>',
            'is_active' => true,
        ]);

        // 6. Create Affiliate Links
        \App\Models\AffiliateLink::factory(5)->create();

        // 7. Create Social Media Links
        $socialMediaLinks = [
            ['platform' => 'Facebook', 'icon' => 'fa-brands fa-facebook', 'url' => 'https://www.facebook.com/newsmedia', 'is_active' => true, 'sort_order' => 1],
            ['platform' => 'Instagram', 'icon' => 'fa-brands fa-instagram', 'url' => 'https://www.instagram.com/newsmedia', 'is_active' => true, 'sort_order' => 2],
            ['platform' => 'X', 'icon' => 'fa-brands fa-x-twitter', 'url' => 'https://x.com/newsmedia', 'is_active' => true, 'sort_order' => 3],
            ['platform' => 'Pinterest', 'icon' => 'fa-brands fa-pinterest', 'url' => 'https://www.pinterest.com/newsmedia', 'is_active' => true, 'sort_order' => 4],
            ['platform' => 'WhatsApp', 'icon' => 'fa-brands fa-whatsapp', 'url' => 'https://wa.me/628123456789', 'is_active' => true, 'sort_order' => 5],
        ];

        foreach ($socialMediaLinks as $social) {
            \App\Models\SocialMedia::updateOrCreate(
                ['platform' => $social['platform']],
                $social
            );
        }

        // Also store in settings table for reference
        $socialMediaSettings = [
            ['key' => 'social_facebook', 'value' => 'https://www.facebook.com/newsmedia'],
            ['key' => 'social_instagram', 'value' => 'https://www.instagram.com/newsmedia'],
            ['key' => 'social_x', 'value' => 'https://x.com/newsmedia'],
            ['key' => 'social_pinterest', 'value' => 'https://www.pinterest.com/newsmedia'],
            ['key' => 'social_whatsapp', 'value' => 'https://wa.me/628123456789'],
        ];

        foreach ($socialMediaSettings as $social) {
            \Illuminate\Support\Facades\DB::table('settings')->updateOrInsert(
                ['key' => $social['key']],
                ['value' => $social['value'], 'updated_at' => now()]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Video;
use App\Models\Category;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::active()->take(3)->get();

        $videos = [
            [
                'title' => 'Breaking News Coverage',
                'description' => 'Latest breaking news from our newsroom',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                'status' => 'published',
                'views_count' => 1250,
            ],
            [
                'title' => 'Tech Innovations Explained',
                'description' => 'Understanding the latest technology trends',
                'youtube_url' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw',
                'youtube_id' => 'jNQXAC9IVRw',
                'thumbnail_url' => 'https://img.youtube.com/vi/jNQXAC9IVRw/maxresdefault.jpg',
                'status' => 'published',
                'views_count' => 856,
            ],
            [
                'title' => 'Interview with Industry Leaders',
                'description' => 'Insights from top industry professionals',
                'youtube_url' => 'https://www.youtube.com/watch?v=9bZkp7q19f0',
                'youtube_id' => '9bZkp7q19f0',
                'thumbnail_url' => 'https://img.youtube.com/vi/9bZkp7q19f0/maxresdefault.jpg',
                'status' => 'published',
                'views_count' => 2150,
            ],
            [
                'title' => 'Global News Roundup',
                'description' => 'This weeks most important stories',
                'youtube_url' => 'https://www.youtube.com/watch?v=RgKAFK5djSk',
                'youtube_id' => 'RgKAFK5djSk',
                'thumbnail_url' => 'https://img.youtube.com/vi/RgKAFK5djSk/maxresdefault.jpg',
                'status' => 'published',
                'views_count' => 1500,
            ],
            [
                'title' => 'Documentary: The Future',
                'description' => 'Exploring possibilities of tomorrow',
                'youtube_url' => 'https://www.youtube.com/watch?v=kJQP7kiw9Fk',
                'youtube_id' => 'kJQP7kiw9Fk',
                'thumbnail_url' => 'https://img.youtube.com/vi/kJQP7kiw9Fk/maxresdefault.jpg',
                'status' => 'published',
                'views_count' => 3400,
            ],
            [
                'title' => 'Behind the Scenes',
                'description' => 'How our newsroom works',
                'youtube_url' => 'https://www.youtube.com/watch?v=OPf0YbXqDm0',
                'youtube_id' => 'OPf0YbXqDm0',
                'thumbnail_url' => 'https://img.youtube.com/vi/OPf0YbXqDm0/maxresdefault.jpg',
                'status' => 'published',
                'views_count' => 780,
            ],
            [
                'title' => 'Expert Panel Discussion',
                'description' => 'Multiple experts discussing key issues',
                'youtube_url' => 'https://www.youtube.com/watch?v=ZmG2Ym4cP-k',
                'youtube_id' => 'ZmG2Ym4cP-k',
                'thumbnail_url' => 'https://img.youtube.com/vi/ZmG2Ym4cP-k/maxresdefault.jpg',
                'status' => 'published',
                'views_count' => 2200,
            ],
            [
                'title' => 'Live Event Coverage',
                'description' => 'Coverage of this weeks major events',
                'youtube_url' => 'https://www.youtube.com/watch?v=xpNH8IFWyK4',
                'youtube_id' => 'xpNH8IFWyK4',
                'thumbnail_url' => 'https://img.youtube.com/vi/xpNH8IFWyK4/maxresdefault.jpg',
                'status' => 'published',
                'views_count' => 1890,
            ],
        ];

        foreach ($videos as $index => $videoData) {
            Video::create([
                ...$videoData,
                'category_id' => $categories->random()->id,
                'user_id' => 1,
                'published_at' => now()->subDays(rand(0, 30)),
            ]);
        }
    }
}

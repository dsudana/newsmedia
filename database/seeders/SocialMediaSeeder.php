<?php

namespace Database\Seeders;

use App\Models\SocialMedia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialMediaSeeder extends Seeder
{
    public function run(): void
    {
        $socialMedia = [
            [
                'platform' => 'Facebook',
                'icon' => 'fab fa-facebook-f',
                'url' => 'https://facebook.com',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'platform' => 'Twitter',
                'icon' => 'fab fa-twitter',
                'url' => 'https://twitter.com',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'platform' => 'Instagram',
                'icon' => 'fab fa-instagram',
                'url' => 'https://instagram.com',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'platform' => 'LinkedIn',
                'icon' => 'fab fa-linkedin-in',
                'url' => 'https://linkedin.com',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'platform' => 'YouTube',
                'icon' => 'fab fa-youtube',
                'url' => 'https://youtube.com',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($socialMedia as $media) {
            SocialMedia::firstOrCreate(
                ['platform' => $media['platform']],
                $media
            );
        }
    }
}

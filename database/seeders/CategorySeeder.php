<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing categories for clean seeding
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Category::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $categories = [
            [
                'name' => 'Depok',
                'icon' => 'fas fa-map-marker-alt',
                'color' => '#FF6B6B',
                'show_in_menu' => true,
                'is_featured' => true,
                'children' => [
                    'Pemerintahan',
                    'Infrastruktur',
                    'Transportasi',
                    'Pendidikan',
                    'Kesehatan',
                    'Ekonomi & UMKM',
                    'Lingkungan',
                    'Hukum & Kriminal',
                    'Peristiwa',
                    'Komunitas',
                ],
            ],
            [
                'name' => 'Nasional',
                'icon' => 'fas fa-flag',
                'color' => '#4ECDC4',
                'show_in_menu' => true,
                'is_featured' => false,
                'children' => [
                    'Politik',
                    'Ekonomi',
                    'Hukum',
                    'Sosial',
                    'Peristiwa',
                ],
            ],
            [
                'name' => 'Bisnis',
                'icon' => 'fas fa-briefcase',
                'color' => '#45B7D1',
                'show_in_menu' => true,
                'is_featured' => false,
                'children' => [
                    'UMKM',
                    'Properti',
                    'Startup',
                    'Finansial',
                    'Karier',
                ],
            ],
            [
                'name' => 'Olahraga',
                'icon' => 'fas fa-trophy',
                'color' => '#F7B731',
                'show_in_menu' => true,
                'is_featured' => false,
                'children' => [
                    'Sepak Bola',
                    'Badminton',
                    'Basket',
                    'Otomotif',
                    'Lainnya',
                ],
            ],
            [
                'name' => 'Lifestyle',
                'icon' => 'fas fa-heart',
                'color' => '#FF9FF3',
                'show_in_menu' => true,
                'is_featured' => false,
                'children' => [
                    'Keluarga',
                    'Fashion',
                    'Kesehatan',
                    'Hiburan',
                    'Komunitas',
                ],
            ],
            [
                'name' => 'Teknologi',
                'icon' => 'fas fa-microchip',
                'color' => '#54A0FF',
                'show_in_menu' => true,
                'is_featured' => false,
                'children' => [
                    'Gadget',
                    'Internet',
                    'AI',
                    'Startup',
                    'Review',
                ],
            ],
            [
                'name' => 'Wisata & Kuliner',
                'icon' => 'fas fa-utensils',
                'color' => '#A29BFE',
                'show_in_menu' => true,
                'is_featured' => false,
                'children' => [
                    'Tempat Wisata',
                    'Kuliner',
                    'Hotel',
                    'Event',
                ],
            ],
            [
                'name' => 'Video',
                'icon' => 'fas fa-video',
                'color' => '#00B894',
                'show_in_menu' => true,
                'is_featured' => false,
                'children' => [
                    'Liputan',
                    'Wawancara',
                    'Podcast',
                    'Live',
                ],
            ],
            [
                'name' => 'Internasional',
                'icon' => 'fas fa-globe',
                'color' => '#2D3436',
                'show_in_menu' => false,
                'is_featured' => false,
                'children' => [
                    'Politik Dunia',
                    'Ekonomi Global',
                    'Konflik',
                    'Peristiwa',
                ],
            ],
            [
                'name' => 'Otomotif',
                'icon' => 'fas fa-car',
                'color' => '#E17055',
                'show_in_menu' => false,
                'is_featured' => false,
                'children' => [
                    'Mobil',
                    'Motor',
                    'Tips & Perawatan',
                    'Review',
                ],
            ],
            [
                'name' => 'Opini',
                'icon' => 'fas fa-pen-fancy',
                'color' => '#6C5CE7',
                'show_in_menu' => false,
                'is_featured' => false,
                'children' => [
                    'Editorial',
                    'Kolom',
                    'Wawancara',
                    'Surat Pembaca',
                ],
            ],
            [
                'name' => 'Kecamatan',
                'icon' => 'fas fa-map',
                'color' => '#FF7675',
                'show_in_menu' => false,
                'is_featured' => false,
                'children' => [
                    'Beji',
                    'Pancoran Mas',
                    'Sukmajaya',
                    'Cimanggis',
                    'Cinere',
                    'Sawangan',
                    'Bojongsari',
                    'Tapos',
                    'Cipayung',
                    'Limo',
                    'Cilodong',
                ],
            ],
        ];

        $sortOrder = 0;

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);

            $parentCategory = Category::create([
                ...$categoryData,
                'slug' => Str::slug($categoryData['name']),
                'sort_order' => $sortOrder++,
            ]);

            foreach ($children as $childIndex => $childName) {
                Category::create([
                    'name' => $childName,
                    'slug' => Str::slug($childName),
                    'parent_id' => $parentCategory->id,
                    'sort_order' => $childIndex,
                    'show_in_menu' => false,
                    'is_featured' => false,
                ]);
            }
        }
    }
}

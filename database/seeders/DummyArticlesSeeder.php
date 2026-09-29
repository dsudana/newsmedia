<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyArticlesSeeder extends Seeder
{
    public function run(): void
    {
        // Get first user or create one
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@newsmedia.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Image URLs for different categories
        $ekonomiImages = [
            'https://images.unsplash.com/photo-1611432579699-484f7990f556?w=800&q=80',
            'https://images.unsplash.com/photo-1460925895917-adf4198897bb?w=800&q=80',
            'https://images.unsplash.com/photo-1518186285646-354e2e4db6b1?w=800&q=80',
        ];

        $pendidikanImages = [
            'https://images.unsplash.com/photo-1427504494785-1a4a2a5fb82e?w=800&q=80',
            'https://images.unsplash.com/photo-1497633762265-25c6b28e8025?w=800&q=80',
            'https://images.unsplash.com/photo-1509662176150-766266f84a0e?w=800&q=80',
        ];

        $olahragaImages = [
            'https://images.unsplash.com/photo-1517836357463-d25ddfcb52c2?w=800&q=80',
            'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&q=80',
            'https://images.unsplash.com/photo-1540747913ee8516a959ad16biased1aaeeb3?w=800&q=80',
        ];

        // Get or create categories
        $ekonomi = Category::firstOrCreate(
            ['slug' => 'ekonomi'],
            ['name' => 'Ekonomi']
        );

        $pendidikan = Category::firstOrCreate(
            ['slug' => 'pendidikan'],
            ['name' => 'Pendidikan']
        );

        $olahraga = Category::firstOrCreate(
            ['slug' => 'olahraga'],
            ['name' => 'Olahraga']
        );

        // Create 3 articles for Ekonomi
        foreach ($ekonomiImages as $i => $image) {
            Article::create([
                'user_id' => $user->id,
                'category_id' => $ekonomi->id,
                'title' => 'Pertumbuhan Ekonomi Indonesia Capai 5% di Kuartal III - Berita ' . ($i + 1),
                'slug' => 'ekonomi-pertumbuhan-' . ($i + 1) . '-' . uniqid(),
                'excerpt' => 'Indonesia berhasil mencapai pertumbuhan ekonomi sebesar 5% pada kuartal ketiga tahun ini, melampaui ekspektasi analis.',
                'content' => '<p>Indonesia berhasil mencapai pertumbuhan ekonomi sebesar 5% pada kuartal ketiga tahun ini, melampaui ekspektasi analis. Pertumbuhan ini didorong oleh meningkatnya konsumsi domestik dan investasi di sektor manufaktur.</p><p>Direktur Jenderal Statistik mengatakan bahwa perekonomian Indonesia tetap resilient meskipun menghadapi tantangan global.</p>',
                'featured_image' => $image,
                'published_at' => now()->subDays($i + 1),
                'status' => 'published',
            ]);
        }

        // Create 3 articles for Pendidikan
        foreach ($pendidikanImages as $i => $image) {
            Article::create([
                'user_id' => $user->id,
                'category_id' => $pendidikan->id,
                'title' => 'Kurikulum Merdeka Terus Berkembang di Sekolah-Sekolah - Artikel ' . ($i + 1),
                'slug' => 'pendidikan-kurikulum-' . ($i + 1) . '-' . uniqid(),
                'excerpt' => 'Program Kurikulum Merdeka telah diadopsi oleh ribuan sekolah di seluruh Indonesia untuk meningkatkan kualitas pendidikan.',
                'content' => '<p>Program Kurikulum Merdeka telah diadopsi oleh ribuan sekolah di seluruh Indonesia untuk meningkatkan kualitas pendidikan. Pemerintah berkomitmen untuk terus memberikan dukungan kepada sekolah-sekolah yang mengimplementasikan kurikulum baru ini.</p><p>Kurikulum ini dirancang untuk memberikan kebebasan kepada sekolah dalam menentukan strategi pembelajaran yang sesuai dengan kebutuhan lokal.</p>',
                'featured_image' => $image,
                'published_at' => now()->subDays($i + 4),
                'status' => 'published',
            ]);
        }

        // Create 3 articles for Olahraga
        foreach ($olahragaImages as $i => $image) {
            Article::create([
                'user_id' => $user->id,
                'category_id' => $olahraga->id,
                'title' => 'Timnas Indonesia Menang 3-1 Lawan Vietnam - Update ' . ($i + 1),
                'slug' => 'olahraga-timnas-' . ($i + 1) . '-' . uniqid(),
                'excerpt' => 'Timnas Indonesia berhasil meraih kemenangan 3-1 atas Vietnam dalam pertandingan kualifikasi Piala Asia 2024.',
                'content' => '<p>Timnas Indonesia berhasil meraih kemenangan 3-1 atas Vietnam dalam pertandingan kualifikasi Piala Asia 2024. Dua gol dicetak oleh striker andalan Marselino Ferdinan, sementara satu gol lainnya dicetak oleh gelandang Hansamu Yama.</p><p>Kemenangan ini menempatkan Indonesia di posisi teratas grup dengan 9 poin dari 3 pertandingan.</p>',
                'featured_image' => $image,
                'published_at' => now()->subDays($i + 7),
                'status' => 'published',
            ]);
        }
    }
}

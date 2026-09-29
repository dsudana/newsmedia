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
        for ($i = 1; $i <= 3; $i++) {
            Article::create([
                'user_id' => $user->id,
                'category_id' => $ekonomi->id,
                'title' => 'Pertumbuhan Ekonomi Indonesia Capai 5% di Kuartal III - Berita ' . $i,
                'slug' => 'ekonomi-pertumbuhan-' . $i . '-' . uniqid(),
                'excerpt' => 'Indonesia berhasil mencapai pertumbuhan ekonomi sebesar 5% pada kuartal ketiga tahun ini, melampaui ekspektasi analis.',
                'content' => '<p>Indonesia berhasil mencapai pertumbuhan ekonomi sebesar 5% pada kuartal ketiga tahun ini, melampaui ekspektasi analis. Pertumbuhan ini didorong oleh meningkatnya konsumsi domestik dan investasi di sektor manufaktur.</p><p>Direktur Jenderal Statistik mengatakan bahwa perekonomian Indonesia tetap resilient meskipun menghadapi tantangan global.</p>',
                'published_at' => now()->subDays($i),
                'status' => 'published',
            ]);
        }

        // Create 3 articles for Pendidikan
        for ($i = 1; $i <= 3; $i++) {
            Article::create([
                'user_id' => $user->id,
                'category_id' => $pendidikan->id,
                'title' => 'Kurikulum Merdeka Terus Berkembang di Sekolah-Sekolah - Artikel ' . $i,
                'slug' => 'pendidikan-kurikulum-' . $i . '-' . uniqid(),
                'excerpt' => 'Program Kurikulum Merdeka telah diadopsi oleh ribuan sekolah di seluruh Indonesia untuk meningkatkan kualitas pendidikan.',
                'content' => '<p>Program Kurikulum Merdeka telah diadopsi oleh ribuan sekolah di seluruh Indonesia untuk meningkatkan kualitas pendidikan. Pemerintah berkomitmen untuk terus memberikan dukungan kepada sekolah-sekolah yang mengimplementasikan kurikulum baru ini.</p><p>Kurikulum ini dirancang untuk memberikan kebebasan kepada sekolah dalam menentukan strategi pembelajaran yang sesuai dengan kebutuhan lokal.</p>',
                'published_at' => now()->subDays($i + 3),
                'status' => 'published',
            ]);
        }

        // Create 3 articles for Olahraga
        for ($i = 1; $i <= 3; $i++) {
            Article::create([
                'user_id' => $user->id,
                'category_id' => $olahraga->id,
                'title' => 'Timnas Indonesia Menang 3-1 Lawan Vietnam - Update ' . $i,
                'slug' => 'olahraga-timnas-' . $i . '-' . uniqid(),
                'excerpt' => 'Timnas Indonesia berhasil meraih kemenangan 3-1 atas Vietnam dalam pertandingan kualifikasi Piala Asia 2024.',
                'content' => '<p>Timnas Indonesia berhasil meraih kemenangan 3-1 atas Vietnam dalam pertandingan kualifikasi Piala Asia 2024. Dua gol dicetak oleh striker andalan Marselino Ferdinan, sementara satu gol lainnya dicetak oleh gelandang Hansamu Yama.</p><p>Kemenangan ini menempatkan Indonesia di posisi teratas grup dengan 9 poin dari 3 pertandingan.</p>',
                'published_at' => now()->subDays($i + 6),
                'status' => 'published',
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Keyword;
use App\Models\Category;
use Illuminate\Database\Seeder;

class KeywordSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        $keywords = [
            // Politics
            ['keyword' => 'Politik terkini Indonesia', 'intent' => 'informational', 'category' => 'Politik'],
            ['keyword' => 'Kebijakan pemerintah 2026', 'intent' => 'informational', 'category' => 'Politik'],
            ['keyword' => 'Pemilihan umum Indonesia', 'intent' => 'informational', 'category' => 'Politik'],

            // Business
            ['keyword' => 'Berita bisnis terbaru', 'intent' => 'informational', 'category' => 'Business'],
            ['keyword' => 'Pasar saham Indonesia', 'intent' => 'informational', 'category' => 'Business'],
            ['keyword' => 'Startup Indonesia terbaru', 'intent' => 'navigational', 'category' => 'Business'],

            // Technology
            ['keyword' => 'Teknologi AI terbaru', 'intent' => 'informational', 'category' => 'Technology'],
            ['keyword' => 'Smartphone terbaru 2026', 'intent' => 'commercial', 'category' => 'Technology'],
            ['keyword' => 'Aplikasi mobile populer', 'intent' => 'informational', 'category' => 'Technology'],

            // Entertainment
            ['keyword' => 'Berita hiburan selebriti', 'intent' => 'informational', 'category' => 'Entertainment'],
            ['keyword' => 'Film Indonesia terbaru', 'intent' => 'informational', 'category' => 'Entertainment'],
            ['keyword' => 'Konser musik Indonesia 2026', 'intent' => 'informational', 'category' => 'Entertainment'],

            // Health
            ['keyword' => 'Tips kesehatan penting', 'intent' => 'informational', 'category' => 'Health'],
            ['keyword' => 'Pandemi COVID-19 terbaru', 'intent' => 'informational', 'category' => 'Health'],
            ['keyword' => 'Nutrisi diet sehat', 'intent' => 'informational', 'category' => 'Health'],

            // Sports
            ['keyword' => 'Berita olahraga Indonesia', 'intent' => 'informational', 'category' => 'Sports'],
            ['keyword' => 'Piala Dunia 2026', 'intent' => 'informational', 'category' => 'Sports'],
            ['keyword' => 'Sepak bola Indonesia terkini', 'intent' => 'informational', 'category' => 'Sports'],
        ];

        foreach ($keywords as $data) {
            $category = Category::where('name', $data['category'])->first();
            if ($category) {
                Keyword::create([
                    'category_id' => $category->id,
                    'keyword' => $data['keyword'],
                    'description' => 'Keyword untuk artikel tentang ' . $data['keyword'],
                    'intent' => $data['intent'],
                    'focus_tone' => 'professional',
                    'target_words' => 2000,
                    'status' => 'pending',
                ]);
            }
        }
    }
}

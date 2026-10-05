<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class BreakingNewsSeeder extends Seeder
{
    public function run(): void
    {
        // Mark top 2 articles as breaking news for testing
        Article::published()
            ->orderByDesc('views_count')
            ->take(2)
            ->update([
                'is_breaking' => true,
                'priority' => 9,
                'breaking_at' => now(),
            ]);

        // Set priority for next 3 articles
        Article::published()
            ->whereNotIn('id', Article::where('is_breaking', true)->pluck('id'))
            ->orderByDesc('views_count')
            ->take(3)
            ->update(['priority' => 7]);

        echo "Breaking news seeder completed!\n";
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class GenerateDummyImages extends Command
{
    protected $signature = 'generate:dummy-images';
    protected $description = 'Generate dummy placeholder images for all articles';

    public function handle()
    {
        $articles = Article::all();
        $count = 0;

        $this->withProgressBar($articles, function ($article) use (&$count) {
            if (!$article->featured_image) {
                $imageId = ($article->id % 100) + 1;
                $article->featured_image = "https://picsum.photos/800/600?random={$article->id}";
                $article->save();
                $count++;
            }
        });

        $this->newLine();
        $this->info("✅ Generated dummy images for {$count} articles");
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class CleanupOldArticles extends Command
{
    protected $signature = 'articles:cleanup-old';
    protected $description = 'Remove old Lorem Ipsum articles that are not from WordPress import';

    public function handle()
    {
        $this->info('Cleaning up old Lorem Ipsum articles...');

        $deleted = Article::where('title', 'like', '%doloremque%')
            ->orWhere('title', 'like', '%assumenda%')
            ->orWhere('title', 'like', '%voluptatum%')
            ->orWhere('title', 'like', '%occaecati%')
            ->orWhere('title', 'like', '%cupiditate%')
            ->delete();

        $this->info("Deleted: {$deleted} old Lorem Ipsum articles");

        $remaining = Article::count();
        $this->info("Remaining articles: {$remaining}");

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class CleanArticleContent extends Command
{
    protected $signature = 'articles:clean-content';
    protected $description = 'Clean and format article content - remove unnecessary HTML tags';

    public function handle()
    {
        $this->info('Cleaning article content...');

        $articles = Article::all();
        $updated = 0;

        foreach ($articles as $article) {
            $originalContent = $article->content;
            $cleanedContent = $this->cleanContent($originalContent);

            if ($cleanedContent !== $originalContent) {
                $article->update(['content' => $cleanedContent]);
                $this->line("Cleaned: {$article->title}");
                $updated++;
            }
        }

        $this->info("\n=== Summary ===");
        $this->info("Cleaned: {$updated} articles");

        return 0;
    }

    private function cleanContent(string $content): string
    {
        // Remove WordPress block comments
        $content = preg_replace('/<!-- \/?wp:[^>]*-->/is', '', $content);

        // Remove figure tags but keep the content inside
        $content = preg_replace('/<figure[^>]*>|<\/figure>/i', '', $content);

        // Remove img tags (images should be in featured_image only)
        $content = preg_replace('/<img[^>]*>/i', '', $content);

        // Remove horizontal lines
        $content = preg_replace('/<hr[^>]*>/i', '', $content);

        // Replace <em> tags with nothing (remove emphasis tags)
        $content = preg_replace('/<\/?em>/i', '', $content);

        // Replace <strong> tags with nothing
        $content = preg_replace('/<\/?strong>/i', '', $content);

        // Replace <i> tags with nothing
        $content = preg_replace('/<\/?i>/i', '', $content);

        // Replace <b> tags with nothing
        $content = preg_replace('/<\/?b>/i', '', $content);

        // Clean up multiple <p> tags - replace multiple consecutive </p><p> with single line break
        $content = preg_replace('/<\/p>\s*<p>/i', '</p><p>', $content);

        // Remove empty paragraphs
        $content = preg_replace('/<p>\s*&nbsp;\s*<\/p>/i', '', $content);
        $content = preg_replace('/<p>\s*<\/p>/i', '', $content);

        // Wrap paragraphs in clean <p> tags
        $paragraphs = explode('</p>', $content);
        $cleanParagraphs = [];

        foreach ($paragraphs as $para) {
            $para = trim($para);
            $para = str_replace('<p>', '', $para);
            $para = trim($para);

            if (!empty($para)) {
                $cleanParagraphs[] = $para;
            }
        }

        // Reconstruct content as plain text with line breaks (no <p> tags)
        $content = implode("\n\n", $cleanParagraphs);

        // Clean up multiple line breaks
        $content = preg_replace('/\n\n\n+/', "\n\n", $content);

        // Final trim
        $content = trim($content);

        return $content;
    }
}

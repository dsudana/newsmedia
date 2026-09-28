<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleMeta;
use App\Models\ArticleFaq;
use App\Models\Keyword;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ArticleGeneratorService
{
    protected $apiKey;
    protected $apiUrl = 'https://api.anthropic.com/v1/messages';
    protected $model = 'claude-3-5-sonnet-20241022';

    public function __construct()
    {
        $this->apiKey = config('services.anthropic.key');
    }

    public function generate(Keyword $keyword): ?Article
    {
        if (!$this->apiKey) {
            throw new \Exception('Anthropic API key not configured');
        }

        try {
            $keyword->update(['status' => 'processing']);

            $prompt = $this->buildPrompt($keyword);
            $response = $this->callClaude($prompt);
            $parsed = $this->parseResponse($response);

            $article = Article::create([
                'user_id' => auth()->id() ?? 1,
                'category_id' => $keyword->category_id,
                'title' => $parsed['title'],
                'slug' => $this->uniqueSlug($parsed['title']),
                'excerpt' => $parsed['excerpt'],
                'content' => $parsed['content'],
                'meta_title' => $parsed['meta_title'],
                'meta_description' => $parsed['meta_description'],
                'status' => 'draft',
                'ai_provider' => 'claude',
                'seo_score' => $this->calculateSeoScore($parsed, $parsed['content']),
            ]);

            ArticleMeta::create([
                'article_id' => $article->id,
                'meta_title' => $parsed['meta_title'],
                'meta_description' => $parsed['meta_description'],
                'focus_keyword' => $keyword->keyword,
                'keywords_used' => $parsed['keywords_used'] ?? [],
            ]);

            if (!empty($parsed['faqs'])) {
                foreach ($parsed['faqs'] as $index => $faq) {
                    ArticleFaq::create([
                        'article_id' => $article->id,
                        'question' => $faq['question'],
                        'answer' => $faq['answer'],
                        'order' => $index,
                    ]);
                }
            }

            $article->keywords()->attach($keyword->id);

            $keyword->update([
                'status' => 'done',
                'generated_at' => now(),
            ]);

            return $article;
        } catch (\Exception $e) {
            $keyword->update([
                'status' => 'failed',
                'error_msg' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    protected function buildPrompt(Keyword $keyword): string
    {
        $category = $keyword->category->name;
        $tone = $keyword->focus_tone ?? 'professional';
        $targetWords = $keyword->target_words ?? 2000;

        return <<<PROMPT
Anda adalah seorang penulis konten berita profesional. Buatlah artikel berita dalam Bahasa Indonesia berkualitas tinggi dengan spesifikasi berikut:

Kategori: {$category}
Keyword Fokus: {$keyword->keyword}
Target Jumlah Kata: {$targetWords}
Nada: {$tone}

Artikel harus:
1. Informatif dan akurat
2. SEO-optimized dengan focus keyword muncul minimal 3x
3. Memiliki struktur yang jelas dengan heading H2 dan H3
4. Mengandung minimal 3 FAQ yang relevan
5. Memiliki excerpt yang menarik (150-200 karakter)

Berikan respons dalam format JSON YANG VALID (tanpa markdown, pure JSON):
{
    "title": "Judul artikel (60-70 karakter)",
    "excerpt": "Ringkasan artikel singkat",
    "content": "Isi artikel HTML dengan <h2>, <h3>, <p>, <ul>",
    "meta_title": "SEO title (30-60 karakter)",
    "meta_description": "Deskripsi SEO (120-160 karakter)",
    "keywords_used": ["keyword1", "keyword2", "keyword3"],
    "faqs": [
        {"question": "Pertanyaan 1?", "answer": "Jawaban lengkap..."},
        {"question": "Pertanyaan 2?", "answer": "Jawaban lengkap..."},
        {"question": "Pertanyaan 3?", "answer": "Jawaban lengkap..."}
    ]
}

PASTIKAN JSON valid dan bisa di-parse tanpa error.
PROMPT;
    }

    protected function callClaude($prompt): string
    {
        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
            'anthropic-version' => '2023-06-01',
        ])->post($this->apiUrl, [
            'model' => $this->model,
            'max_tokens' => 4000,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
        ]);

        if ($response->failed()) {
            throw new \Exception('Anthropic API error: ' . $response->body());
        }

        $data = $response->json();
        return $data['content'][0]['text'] ?? '';
    }

    protected function parseResponse(string $response): array
    {
        preg_match('/\{.*\}/s', $response, $matches);
        if (empty($matches)) {
            throw new \Exception('Could not extract JSON from response');
        }

        $json = json_decode($matches[0], true);
        if (!$json) {
            throw new \Exception('Invalid JSON in response: ' . json_last_error_msg());
        }

        return $json;
    }

    protected function uniqueSlug($title): string
    {
        $slug = Str::slug($title);
        $count = Article::where('slug', 'like', $slug . '%')->count();
        return $count ? "{$slug}-" . Str::random(6) : $slug;
    }

    protected function calculateSeoScore($parsed, $content): int
    {
        $score = 0;

        if (strlen($parsed['title'] ?? '') >= 30 && strlen($parsed['title']) <= 70) $score += 20;
        if (strlen($parsed['meta_description'] ?? '') >= 120 && strlen($parsed['meta_description']) <= 160) $score += 20;
        if (str_word_count(strip_tags($content)) >= 1000) $score += 20;
        if (substr_count(strtolower($content), '<h2') >= 2) $score += 10;
        if (substr_count(strtolower($content), '<h3') >= 2) $score += 10;
        if (!empty($parsed['keywords_used']) && count($parsed['keywords_used']) >= 3) $score += 20;

        return min(100, $score);
    }
}

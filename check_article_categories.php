<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ARTIKEL DI KATEGORI POLITIK ===\n";
$politikCategory = \App\Models\Category::where('slug', 'politik')->first();
if ($politikCategory) {
    $articles = $politikCategory->articles()->get();
    echo "Kategori ID: {$politikCategory->id}\n";
    echo "Total artikel: " . $articles->count() . "\n";
    foreach($articles as $article) {
        $hasFeaturedImage = !empty($article->featured_image) ? 'YES' : 'NO';
        echo "  - {$article->title} (ID: {$article->id}, category_id: {$article->category_id}, featured_image: {$hasFeaturedImage})\n";
    }
}

echo "\n=== ARTIKEL DI KATEGORI PEMERINTAHAN (info-kota) ===\n";
$pemerintahanCategory = \App\Models\Category::where('slug', 'info-kota')->first();
if ($pemerintahanCategory) {
    $articles = $pemerintahanCategory->articles()->limit(3)->get();
    echo "Kategori ID: {$pemerintahanCategory->id}\n";
    echo "Total artikel: " . $pemerintahanCategory->articles()->count() . "\n";
    echo "Sample (3 artikel):\n";
    foreach($articles as $article) {
        $hasFeaturedImage = !empty($article->featured_image) ? 'YES' : 'NO';
        echo "  - {$article->title} (ID: {$article->id}, category_id: {$article->category_id}, featured_image: {$hasFeaturedImage})\n";
    }
}

echo "\n=== ARTIKEL DI KATEGORI PENDIDIKAN (ilmu) ===\n";
$pendidikanCategory = \App\Models\Category::where('slug', 'ilmu')->first();
if ($pendidikanCategory) {
    $articles = $pendidikanCategory->articles()->get();
    echo "Kategori ID: {$pendidikanCategory->id}\n";
    echo "Total artikel: " . $articles->count() . "\n";
    foreach($articles as $article) {
        $hasFeaturedImage = !empty($article->featured_image) ? 'YES' : 'NO';
        echo "  - {$article->title} (ID: {$article->id}, category_id: {$article->category_id}, featured_image: {$hasFeaturedImage})\n";
    }
}

echo "\n=== SUMMARY ===\n";
echo "Total articles in DB: " . \App\Models\Article::count() . "\n";
echo "Articles with featured_image: " . \App\Models\Article::whereNotNull('featured_image')->where('featured_image', '!=', '')->count() . "\n";

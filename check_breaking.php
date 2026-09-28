<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$section = \App\Models\HomepageSection::where('section_type', 'breaking_news_strip')->first();
if ($section) {
    echo "Breaking News Strip Found!\n";
    echo "Status: " . ($section->status ? "ACTIVE" : "INACTIVE") . "\n";
    echo "Order: " . $section->order . "\n";
    echo "Page Type: " . $section->page_type . "\n";
    echo "Config: " . json_encode($section->config, JSON_PRETTY_PRINT) . "\n";
    echo "\nArticles that will display: " . \App\Models\Article::where('status', 'published')->whereNotNull('featured_image')->where('featured_image', '!=', '')->count();
} else {
    echo "No breaking_news_strip section found!";
}

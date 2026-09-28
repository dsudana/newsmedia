<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$sections = \App\Models\HomepageSection::where('section_type', 'category_grid_section')->get();
foreach($sections as $section) {
    $config = $section->config ?? [];
    $categorySlug = $config['category'] ?? 'none';

    $cat = \App\Models\Category::where('slug', $categorySlug)->first();
    if ($cat) {
        $articleCount = $cat->articles()
            ->where('status', 'published')
            ->whereNotNull('featured_image')
            ->where('featured_image', '!=', '')
            ->count();
        echo "{$section->title}: {$categorySlug} - {$articleCount} articles with featured_image\n";
    } else {
        echo "{$section->title}: {$categorySlug} - CATEGORY NOT FOUND\n";
    }
}

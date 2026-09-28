<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$sections = \App\Models\HomepageSection::where('section_type', 'category_grid_section')->get();
foreach($sections as $section) {
    $config = $section->config ?? [];
    echo "Section: {$section->title}\n";
    echo "Category ID: " . ($config['category_id'] ?? 'not set') . "\n";
    if (isset($config['category_id'])) {
        $cat = \App\Models\Category::find($config['category_id']);
        if ($cat) {
            echo "Category Name: {$cat->name} (slug: {$cat->slug})\n";
            $articleCount = $cat->articles()
                ->where('status', 'published')
                ->whereNotNull('featured_image')
                ->where('featured_image', '!=', '')
                ->count();
            echo "Articles with image: {$articleCount}\n";
        }
    }
    echo "---\n";
}

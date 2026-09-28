<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ALL CATEGORIES ===\n";
$categories = \App\Models\Category::all();
foreach($categories as $cat) {
    $articleCount = $cat->articles()->count();
    $publishedWithImage = $cat->articles()
        ->where('status', 'published')
        ->whereNotNull('featured_image')
        ->where('featured_image', '!=', '')
        ->count();
    echo "ID {$cat->id}: {$cat->name} (slug: {$cat->slug}) - {$articleCount} total, {$publishedWithImage} published with image\n";
}

echo "\n=== DUPLICATE NAMES ===\n";
$duplicates = \App\Models\Category::groupBy('name')
    ->selectRaw('name, COUNT(*) as count, GROUP_CONCAT(id) as ids')
    ->havingRaw('COUNT(*) > 1')
    ->get();

if ($duplicates->isEmpty()) {
    echo "No duplicates found\n";
} else {
    foreach($duplicates as $dup) {
        echo "{$dup->name}: {$dup->ids}\n";
    }
}

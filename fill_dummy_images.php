<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\File;

// Create dummy image directory
$dummyDir = storage_path('app/public/articles/dummy');
if (!file_exists($dummyDir)) {
    File::makeDirectory($dummyDir, 0755, true);
}

// Generate dummy images with different colors
$colors = [
    ['r' => 220, 'g' => 38, 'b' => 38],   // Red
    ['r' => 59, 'g' => 130, 'b' => 246],  // Blue
    ['r' => 34, 'g' => 197, 'b' => 94],   // Green
    ['r' => 249, 'g' => 115, 'b' => 22],  // Orange
    ['r' => 168, 'g' => 85, 'b' => 247],  // Purple
    ['r' => 20, 'g' => 184, 'b' => 166],  // Teal
];

echo "Generating dummy images...\n";
for ($i = 0; $i < 6; $i++) {
    $image = imagecreatetruecolor(800, 450);
    $color = imagecolorallocate(
        $image,
        $colors[$i % count($colors)]['r'],
        $colors[$i % count($colors)]['g'],
        $colors[$i % count($colors)]['b']
    );

    imagefill($image, 0, 0, $color);

    // Add text
    $white = imagecolorallocate($image, 255, 255, 255);
    $fontSize = 5;
    $text = "Dummy Image " . ($i + 1);
    $textX = 300;
    $textY = 210;
    imagestring($image, $fontSize, $textX, $textY, $text, $white);

    $filename = "dummy_" . ($i + 1) . ".jpg";
    $filepath = $dummyDir . '/' . $filename;
    imagejpeg($image, $filepath, 80);
    imagedestroy($image);
    echo "✓ Created: $filename\n";
}

echo "\nUpdating articles without featured_image...\n";

// Get all articles without featured_image
$articles = \App\Models\Article::whereNull('featured_image')
    ->orWhere('featured_image', '')
    ->where('status', 'published')
    ->get();

echo "Found " . $articles->count() . " articles to update\n";

foreach($articles as $key => $article) {
    $dummyNumber = ($key % 6) + 1;
    $imagePath = "articles/dummy/dummy_" . $dummyNumber . ".jpg";
    $article->featured_image = $imagePath;
    $article->save();
    echo "✓ Updated: {$article->title}\n";
}

echo "\n✅ Done! All articles now have dummy images.\n";

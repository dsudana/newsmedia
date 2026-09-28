<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$categoryMap = [
    'Pemerintahan' => 'info-kota',   // Use the one with articles (ID 12)
    'Pendidikan' => 'ilmu',          // Use the one with articles (ID 14)
    'Politik' => 'politik',          // Use existing one (ID 1)
];

$sections = \App\Models\HomepageSection::where('section_type', 'category_grid_section')->get();
foreach($sections as $section) {
    if (isset($categoryMap[$section->title])) {
        $config = $section->config ?? [];
        $config['category'] = $categoryMap[$section->title];
        $section->config = $config;
        $section->save();
        echo "Updated: {$section->title} -> {$categoryMap[$section->title]}\n";
    }
}

echo "\nDone! Verify the categories now have articles.\n";

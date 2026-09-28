<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$categories = \App\Models\Category::all();
foreach($categories as $cat) {
    $count = $cat->articles()
        ->where('status', 'published')
        ->whereNotNull('featured_image')
        ->where('featured_image', '!=', '')
        ->count();
    echo $cat->name . ': ' . $count . ' articles' . PHP_EOL;
}

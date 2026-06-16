<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
echo "Tags in DB: " . App\Models\Tag::count() . "\n";
echo "All tags: " . App\Models\Tag::pluck('name')->implode(', ') . "\n\n";
$listings = App\Models\Listing::with('tags')->get();
foreach ($listings as $l) {
    echo $l->id . ': ' . $l->title . ' => TAGS: ' . $l->tags->pluck('name')->implode(', ') . "\n";
}

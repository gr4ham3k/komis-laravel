<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$images = App\Models\Image::all();
foreach ($images as $img) {
    $diskPath = storage_path('app/public/' . $img->file_name);
    $exists = file_exists($diskPath) ? 'OK' : 'MISSING';
    echo "$exists: DB={$img->file_name} Disk=$diskPath\n";
}
echo "\n--- ALL TAGS ---\n";
echo "Count: " . App\Models\Tag::count() . "\n";
echo "Names: " . App\Models\Tag::pluck('name')->implode(', ') . "\n";

echo "\n--- LISTING TAGS ---\n";
$listings = App\Models\Listing::with('tags')->get();
foreach ($listings as $l) {
    echo $l->id . ': ' . $l->title . ' => ' . $l->tags->pluck('name')->implode(', ') . "\n";
}

echo "\n--- listing_tag TABLE ---\n";
$pivot = DB::table('listing_tags')->get();
echo "Count: " . $pivot->count() . "\n";
foreach ($pivot as $p) {
    echo "  listing_id={$p->listing_id} tag_id={$p->tag_id}\n";
}

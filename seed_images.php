<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

$listings = Listing::with('brand')->get();

if ($listings->isEmpty()) {
    echo "No listings found in the database.\n";
    exit;
}

// Make sure the directory exists
Storage::disk('public')->makeDirectory('listings');

foreach ($listings as $listing) {
    echo "Processing listing: {$listing->title}\n";
    
    // Clear existing images for this listing
    $existingImages = $listing->images;
    foreach($existingImages as $img) {
        Storage::disk('public')->delete('listings/' . $img->file_name);
        $img->delete();
    }
    $listing->images()->detach();

    $brandName = $listing->brand ? strtolower($listing->brand->name) : 'car';
    // Remove spaces and special chars for url
    $brandName = preg_replace('/[^a-z0-9]/', '', $brandName);
    
    for ($i = 1; $i <= 3; $i++) {
        // Use LoremFlickr with car and brand keywords
        $randomLock = rand(1, 9999);
        $imageUrl = "https://loremflickr.com/800/600/car,{$brandName}?lock={$randomLock}";
        
        echo " - Downloading {$imageUrl} ... ";
        
        // Use file_get_contents with a timeout
        $ctx = stream_context_create([
            'http' => ['timeout' => 10]
        ]);
        
        $imageContent = @file_get_contents($imageUrl, false, $ctx);
        
        if ($imageContent) {
            $filename = Str::uuid() . '.jpg';
            Storage::disk('public')->put('listings/' . $filename, $imageContent);
            
            $image = \App\Models\Image::create([
                'file_name' => $filename,
                'original_name' => 'loremflickr.jpg',
                'file_type' => 'image/jpeg',
            ]);
            
            $listing->images()->attach($image->id, ['sort_order' => $i]);

            echo "OK ({$filename})\n";
        } else {
            echo "FAILED\n";
        }
    }
}
echo "Done.\n";

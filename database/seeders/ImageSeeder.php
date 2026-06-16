<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            'bmw1.jpg',
            'bmw2.jpg',
            'audi1.jpg',
            'audi2.jpg',
            'merc1.jpg',
            'merc2.jpg',
            'golf1.jpg',
            'golf2.jpg',
            'rav4_1.jpg',
            'rav4_2.jpg',
            'octavia1.jpg',
            'octavia2.jpg',
        ];

        foreach ($images as $img) {

            $sourcePath = database_path('seed-images/' . $img);

            $uuid = (string) Str::uuid() . '.jpg';

            $storagePath = 'listings/' . $uuid;

            Storage::disk('public')->put(
                $storagePath,
                file_get_contents($sourcePath)
            );

            Image::create([
                'original_name' => $img,
                'file_name' => $storagePath,
                'file_type' => 'image/jpeg',
            ]);
        }
    }
}

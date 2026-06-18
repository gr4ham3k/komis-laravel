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

            'focus1.jpg',
            'focus2.jpg',
            'astra1.jpg',
            'astra2.jpg',
            'passat1.jpg',
            'passat2.jpg',
            'corolla1.jpg',
            'corolla2.jpg',
            'x5_1.jpg',
            'x5_2.jpg',
            'a6_1.jpg',
            'a6_2.jpg',
            'eclass1.jpg',
            'eclass2.jpg',
            'superb1.jpg',
            'superb2.jpg',
            'tucson1.jpg',
            'tucson2.jpg',
            'sportage1.jpg',
            'sportage2.jpg',
            'qashqai1.jpg',
            'qashqai2.jpg',
            'cx5_1.jpg',
            'cx5_2.jpg',
            'xc60_1.jpg',
            'xc60_2.jpg',
            'civic1.jpg',
            'civic2.jpg',
            '3008_1.jpg',
            '3008_2.jpg',
            'leon1.jpg',
            'leon2.jpg',
            '500_1.jpg',
            '500_2.jpg',
        ];

        foreach ($images as $img) {
            if (Image::where('original_name', $img)->exists()) {
                continue;
            }

            $sourcePath = database_path('seed-images/' . $img);
            if (!file_exists($sourcePath)) {
                continue;
            }

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

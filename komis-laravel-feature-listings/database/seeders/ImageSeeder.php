<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Image;
use Illuminate\Support\Str;

class ImageSeeder extends Seeder
{
    public function run(): void
    {
        Image::create([
            'id' => 1,
            'original_name' => 'bmw1.jpg',
            'file_name' => 'bmw1.jpg',
            'file_type' => 'image/jpeg',
        ]);

        Image::create([
            'id' => 2,
            'original_name' => 'bmw2.jpg',
            'file_name' => 'bmw2.jpg',
            'file_type' => 'image/jpeg',
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Image;

class ImageSeeder extends Seeder
{
    public function run(): void
    {
        Image::updateOrCreate(['id' => 1], [
            'id' => 1,
            'original_name' => 'bmw1.jpg',
            'file_name' => 'bmw1.jpg',
            'file_type' => 'image/jpeg',
        ]);

        Image::updateOrCreate(['id' => 2], [
            'id' => 2,
            'original_name' => 'bmw2.jpg',
            'file_name' => 'bmw2.jpg',
            'file_type' => 'image/jpeg',
        ]);
    }
}

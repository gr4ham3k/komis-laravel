<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'miejskie',
            'rodzinne',
            'wyscigowe',
            'ekonomiczne',
            'premium',
            'offroad',
            'sportowe',
        ];

        foreach ($tags as $name) {
            Tag::query()->firstOrCreate(['name' => $name]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Bezwypadkowy',
            'Pierwszy właściciel',
            'Serwisowany w ASO',
            'Garażowany',
            'Zarejestrowany w Polsce',
            'Faktura VAT',
            'Możliwa zamiana',
            'Uszkodzony',
            'Tuning',
            'Automat',
            'Manual',
            'Klimatyzacja',
            'Skórzana tapicerka',
            'Elektryczne szyby',
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag
            ]);
        }
    }
}

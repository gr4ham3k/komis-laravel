<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Automat',
            'Bezwypadkowy',
            'Elektryczne szyby',
            'Faktura VAT',
            'Garażowany',
            'Klimatyzacja',
            'Manual',
            'Możliwa zamiana',
            'Pierwszy właściciel',
            'Serwisowany w ASO',
            'Skórzana tapicerka',
            'Tuning',
            'Uszkodzony',
            'Zarejestrowany w Polsce',
        ];

        foreach ($tags as $name) {
            Tag::query()->updateOrCreate(['name' => $name], ['name' => $name]);
        }
    }
}

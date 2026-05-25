<?php

namespace Database\Seeders;

use App\Models\BodyType;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Fuel;
use App\Models\Image;
use App\Models\Listing;
use App\Models\Tag;
use App\Models\Transmission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ListingSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->firstOrCreate(
            ['email' => 'test@test.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'is_admin' => false,
                'is_banned' => false,
            ]
        );

        $brands = Brand::query()->pluck('id', 'name');
        $models = CarModel::query()->get()->groupBy('brand_id');
        $fuels = Fuel::query()->pluck('id', 'name');
        $transmissions = Transmission::query()->pluck('id', 'name');
        $bodyTypes = BodyType::query()->pluck('id', 'name');
        $tagIds = Tag::query()->pluck('id', 'name');

        $rows = [
            [
                'title' => 'BMW Seria 3 320d',
                'brand' => 'BMW',
                'model' => 'Seria 3',
                'fuel' => 'Diesel',
                'transmission' => 'Automatyczna',
                'body' => 'Sedan',
                'price' => 45000,
                'city' => 'Rzeszow',
                'color' => 'Czerwony',
                'year' => 2016,
                'mileage' => 180000,
                'engine_capacity' => 2000,
                'power_hp' => 190,
                'description' => 'Zadbane BMW, serwisowane, stan bardzo dobry.',
                'views_count' => 41,
                'tags' => ['Bezwypadkowy', 'Serwisowany w ASO', 'Automat'],
                'images' => [
                    'https://commons.wikimedia.org/wiki/Special:FilePath/BMW%20320d%20(F30)%20front.JPG',
                    'https://commons.wikimedia.org/wiki/Special:FilePath/BMW%20320d%20Luxury%20(F30)%20front.jpg',
                ],
            ],
            [
                'title' => 'Toyota Corolla 1.8 Hybrid',
                'brand' => 'Toyota',
                'model' => 'Corolla',
                'fuel' => 'Hybryda',
                'transmission' => 'Automatyczna',
                'body' => 'Sedan',
                'price' => 73900,
                'city' => 'Krakow',
                'color' => 'Bialy',
                'year' => 2020,
                'mileage' => 92000,
                'engine_capacity' => 1798,
                'power_hp' => 122,
                'description' => 'Ekonomiczna Corolla Hybrid, krajowa, gotowa do jazdy.',
                'views_count' => 28,
                'tags' => ['Bezwypadkowy', 'Pierwszy właściciel', 'Klimatyzacja'],
                'images' => [
                    'https://commons.wikimedia.org/wiki/Special:FilePath/2020%20Toyota%20Corolla%20Hybrid%20SEG%20(Colombia)%20front%20view.png',
                    'https://commons.wikimedia.org/wiki/Special:FilePath/2020%20Toyota%20Corolla%20Hybrid%20SEG%20(Colombia)%20rear%20view.png',
                ],
            ],
            [
                'title' => 'Audi A4 2.0 TFSI',
                'brand' => 'Audi',
                'model' => 'A4',
                'fuel' => 'Benzyna',
                'transmission' => 'Manualna',
                'body' => 'Sedan',
                'price' => 58900,
                'city' => 'Lublin',
                'color' => 'Granatowy',
                'year' => 2018,
                'mileage' => 136000,
                'engine_capacity' => 1984,
                'power_hp' => 190,
                'description' => 'Audi A4 z dynamicznym silnikiem benzynowym, regularnie serwisowane.',
                'views_count' => 19,
                'tags' => ['Manual', 'Serwisowany w ASO', 'Skórzana tapicerka'],
                'images' => [
                    'https://commons.wikimedia.org/wiki/Special:FilePath/Audi%20A4%20(B9)%20(22174183931).jpg',
                    'https://commons.wikimedia.org/wiki/Special:FilePath/Audi%20A4%20(B9)%20(22174184761).jpg',
                ],
            ],
            [
                'title' => 'BMW Seria 5 530d xDrive',
                'brand' => 'BMW',
                'model' => 'Seria 5',
                'fuel' => 'Diesel',
                'transmission' => 'Automatyczna',
                'body' => 'Sedan',
                'price' => 119000,
                'city' => 'Warszawa',
                'color' => 'Czarny',
                'year' => 2021,
                'mileage' => 78000,
                'engine_capacity' => 2993,
                'power_hp' => 265,
                'description' => 'Bogata wersja, naped xDrive, bardzo dobry stan techniczny.',
                'views_count' => 57,
                'tags' => ['Automat', 'Skórzana tapicerka', 'Faktura VAT'],
                'images' => [
                    'https://commons.wikimedia.org/wiki/Special:FilePath/BMW-G30.JPG',
                    'https://commons.wikimedia.org/wiki/Special:FilePath/BMW%20G30%20M%20Sport%20(2017)%20front.jpg',
                ],
            ],
            [
                'title' => 'Toyota Yaris 1.5',
                'brand' => 'Toyota',
                'model' => 'Yaris',
                'fuel' => 'Benzyna',
                'transmission' => 'Manualna',
                'body' => 'Hatchback',
                'price' => 39900,
                'city' => 'Rzeszow',
                'color' => 'Srebrny',
                'year' => 2017,
                'mileage' => 109000,
                'engine_capacity' => 1496,
                'power_hp' => 111,
                'description' => 'Miejskie auto, tanie w utrzymaniu, idealne na codzienne dojazdy.',
                'views_count' => 16,
                'tags' => ['Manual', 'Bezwypadkowy', 'Elektryczne szyby'],
                'images' => [
                    'https://commons.wikimedia.org/wiki/Special:FilePath/Toyota%20Vitz%20(XP90%26XP130)%20front.JPG',
                    'https://commons.wikimedia.org/wiki/Special:FilePath/Toyota%20Yaris%20(XP130)%20%E2%80%93%20Frontansicht%2C%2021.%20Juli%202012%2C%20Heiligenhaus%20(cropped).jpg',
                ],
            ],
            [
                'title' => 'Audi A3 1.6 TDI',
                'brand' => 'Audi',
                'model' => 'A3',
                'fuel' => 'Diesel',
                'transmission' => 'Manualna',
                'body' => 'Hatchback',
                'price' => 47900,
                'city' => 'Gdansk',
                'color' => 'Szary',
                'year' => 2015,
                'mileage' => 164000,
                'engine_capacity' => 1598,
                'power_hp' => 110,
                'description' => 'Zadbane A3, niskie spalanie i wygodne wyposazenie.',
                'views_count' => 22,
                'tags' => ['Klimatyzacja', 'Zarejestrowany w Polsce', 'Możliwa zamiana'],
                'images' => [
                    'https://commons.wikimedia.org/wiki/Special:FilePath/Audi%20A3%20Sedan%20(8V)%20front.JPG',
                    'https://commons.wikimedia.org/wiki/Special:FilePath/Audi%20A3%20Sportback%208V%20(front).JPG',
                ],
            ],
        ];

        foreach ($rows as $row) {
            $brandId = $brands[$row['brand']] ?? null;
            $modelId = $models[$brandId]?->firstWhere('name', $row['model'])?->id;
            $fuelId = $fuels[$row['fuel']] ?? null;
            $transmissionId = $transmissions[$row['transmission']] ?? null;
            $bodyTypeId = $bodyTypes[$row['body']] ?? null;

            if (! $brandId || ! $modelId || ! $fuelId || ! $transmissionId || ! $bodyTypeId) {
                continue;
            }

            $listing = Listing::query()->updateOrCreate(
                ['user_id' => $user->id, 'title' => $row['title']],
                [
                    'user_id' => $user->id,
                    'brand_id' => $brandId,
                    'model_id' => $modelId,
                    'fuel_id' => $fuelId,
                    'transmission_id' => $transmissionId,
                    'body_type_id' => $bodyTypeId,
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'price' => $row['price'],
                    'status' => 'active',
                    'city' => $row['city'],
                    'color' => $row['color'],
                    'year' => $row['year'],
                    'mileage' => $row['mileage'],
                    'engine_capacity' => $row['engine_capacity'],
                    'power_hp' => $row['power_hp'],
                    'views_count' => $row['views_count'],
                ]
            );

            $syncImages = [];
            foreach (array_values($row['images']) as $index => $imageSource) {
                $image = Image::query()->firstOrCreate(
                    ['file_name' => $imageSource],
                    [
                        'original_name' => Str::slug($row['title']) . '-' . ($index + 1),
                        'file_type' => str_ends_with(strtolower($imageSource), '.png') ? 'image/png' : 'image/jpeg',
                    ]
                );

                $syncImages[$image->id] = ['sort_order' => $index + 1];
            }

            $listing->images()->sync($syncImages);

            $listing->tags()->sync(
                collect($row['tags'])
                    ->map(fn (string $name) => $tagIds[$name] ?? null)
                    ->filter()
                    ->values()
                    ->all()
            );
        }
    }
}

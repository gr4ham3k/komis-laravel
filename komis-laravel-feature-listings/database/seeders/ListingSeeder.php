<?php

namespace Database\Seeders;

use App\Models\BodyType;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Fuel;
use App\Models\Listing;
use App\Models\Tag;
use App\Models\Transmission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ListingSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            User::query()->updateOrCreate(
                ['email' => 'test@test.com'],
                [
                    'name' => 'Test User',
                    'password' => Hash::make('password'),
                    'is_admin' => false,
                    'is_banned' => false,
                ]
            ),
            User::query()->updateOrCreate(
                ['email' => 'ania@example.com'],
                [
                    'name' => 'Ania Kowalska',
                    'password' => Hash::make('password'),
                    'is_admin' => false,
                    'is_banned' => false,
                ]
            ),
            User::query()->updateOrCreate(
                ['email' => 'marek@example.com'],
                [
                    'name' => 'Marek Nowak',
                    'password' => Hash::make('password'),
                    'is_admin' => false,
                    'is_banned' => false,
                ]
            ),
        ];

        $brandId = fn (string $name) => Brand::query()->where('name', $name)->value('id');
        $modelId = fn (string $name) => CarModel::query()->where('name', $name)->value('id');
        $fuelId = fn (string $name) => Fuel::query()->where('name', $name)->value('id');
        $transmissionId = fn (string $name) => Transmission::query()->where('name', $name)->value('id');
        $bodyTypeId = fn (string $name) => BodyType::query()->where('name', $name)->value('id');
        $tagId = fn (string $name) => Tag::query()->where('name', $name)->value('id');

        $seedListings = [
            [
                'user' => $users[0],
                'brand_id' => $brandId('BMW'),
                'model_id' => $modelId('3 Series'),
                'fuel_id' => $fuelId('Diesel'),
                'transmission_id' => $transmissionId('Automatic'),
                'body_type_id' => $bodyTypeId('Sedan'),
                'title' => 'BMW 320d xDrive M Sport',
                'description' => 'Auto serwisowane, nowy rozrzad, komplet dokumentow i dwa kluczyki.',
                'price' => 78900,
                'status' => 'active',
                'city' => 'Rzeszow',
                'views_count' => 124,
                'color' => 'Grafitowy',
                'year' => 2018,
                'mileage' => 146500,
                'engine_capacity' => 1995,
                'power_hp' => 190,
                'tags' => ['premium', 'sportowe'],
                'images' => [1, 2],
            ],
            [
                'user' => $users[1],
                'brand_id' => $brandId('Toyota'),
                'model_id' => $modelId('Yaris'),
                'fuel_id' => $fuelId('Hybrid'),
                'transmission_id' => $transmissionId('Automatic'),
                'body_type_id' => $bodyTypeId('Hatchback'),
                'title' => 'Toyota Yaris Hybrid 1.5',
                'description' => 'Idealna do miasta, niskie spalanie i kamera cofania.',
                'price' => 59900,
                'status' => 'active',
                'city' => 'Krakow',
                'views_count' => 88,
                'color' => 'Bialy',
                'year' => 2020,
                'mileage' => 64000,
                'engine_capacity' => 1490,
                'power_hp' => 116,
                'tags' => ['miejskie', 'ekonomiczne'],
            ],
            [
                'user' => $users[2],
                'brand_id' => $brandId('Audi'),
                'model_id' => $modelId('A4'),
                'fuel_id' => $fuelId('Diesel'),
                'transmission_id' => $transmissionId('Manual'),
                'body_type_id' => $bodyTypeId('Sedan'),
                'title' => 'Audi A4 2.0 TDI',
                'description' => 'Oryginalny przebieg, regularny serwis, bardzo komfortowe auto rodzinne.',
                'price' => 52900,
                'status' => 'active',
                'city' => 'Lublin',
                'views_count' => 51,
                'color' => 'Srebrny',
                'year' => 2015,
                'mileage' => 187000,
                'engine_capacity' => 1968,
                'power_hp' => 150,
                'tags' => ['rodzinne', 'premium'],
            ],
            [
                'user' => $users[0],
                'brand_id' => $brandId('BMW'),
                'model_id' => $modelId('5 Series'),
                'fuel_id' => $fuelId('Petrol'),
                'transmission_id' => $transmissionId('Automatic'),
                'body_type_id' => $bodyTypeId('Sedan'),
                'title' => 'BMW 540i M Performance',
                'description' => 'Mocne i szybkie auto, bogate wyposazenie, head-up i asystenci jazdy.',
                'price' => 159900,
                'status' => 'active',
                'city' => 'Warszawa',
                'views_count' => 205,
                'color' => 'Czarny',
                'year' => 2019,
                'mileage' => 98000,
                'engine_capacity' => 2998,
                'power_hp' => 340,
                'tags' => ['wyscigowe', 'sportowe', 'premium'],
            ],
            [
                'user' => $users[1],
                'brand_id' => $brandId('Toyota'),
                'model_id' => $modelId('Corolla'),
                'fuel_id' => $fuelId('Petrol'),
                'transmission_id' => $transmissionId('Manual'),
                'body_type_id' => $bodyTypeId('Sedan'),
                'title' => 'Toyota Corolla 1.6 Comfort',
                'description' => 'Pewne i tanie w utrzymaniu auto na codzienne dojazdy.',
                'price' => 38900,
                'status' => 'active',
                'city' => 'Poznan',
                'views_count' => 73,
                'color' => 'Niebieski',
                'year' => 2013,
                'mileage' => 212000,
                'engine_capacity' => 1598,
                'power_hp' => 132,
                'tags' => ['ekonomiczne', 'miejskie'],
            ],
            [
                'user' => $users[2],
                'brand_id' => $brandId('Audi'),
                'model_id' => $modelId('A3'),
                'fuel_id' => $fuelId('Petrol'),
                'transmission_id' => $transmissionId('Automatic'),
                'body_type_id' => $bodyTypeId('Hatchback'),
                'title' => 'Audi A3 Sportback 35 TFSI',
                'description' => 'Dynamiczne auto miejskie, swietny stan i niski przebieg.',
                'price' => 104500,
                'status' => 'active',
                'city' => 'Wroclaw',
                'views_count' => 64,
                'color' => 'Czerwony',
                'year' => 2021,
                'mileage' => 47000,
                'engine_capacity' => 1498,
                'power_hp' => 150,
                'tags' => ['miejskie', 'sportowe', 'premium'],
            ],
            [
                'user' => $users[0],
                'brand_id' => $brandId('Toyota'),
                'model_id' => $modelId('Corolla'),
                'fuel_id' => $fuelId('Hybrid'),
                'transmission_id' => $transmissionId('Automatic'),
                'body_type_id' => $bodyTypeId('SUV'),
                'title' => 'Toyota Corolla Cross Hybrid',
                'description' => 'Przestronny SUV rodzinny, bardzo komfortowy i oszczedny.',
                'price' => 132000,
                'status' => 'active',
                'city' => 'Gdansk',
                'views_count' => 44,
                'color' => 'Szary',
                'year' => 2022,
                'mileage' => 22000,
                'engine_capacity' => 1987,
                'power_hp' => 197,
                'tags' => ['rodzinne', 'offroad', 'ekonomiczne'],
            ],
        ];

        foreach ($seedListings as $idx => $data) {
            $tagNames = $data['tags'] ?? [];
            $imageIds = $data['images'] ?? [];
            $user = $data['user'];
            unset($data['tags'], $data['images'], $data['user']);

            $listing = Listing::query()->create([
                ...$data,
                'user_id' => $user->id,
            ]);

            $tagIds = collect($tagNames)
                ->map(fn (string $name) => $tagId($name))
                ->filter()
                ->values()
                ->all();

            if (! empty($tagIds)) {
                $listing->tags()->sync($tagIds);
            }

            if (! empty($imageIds)) {
                $listing->images()->syncWithPivotValues(
                    $imageIds,
                    ['sort_order' => 1]
                );
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Listing;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Fuel;
use App\Models\Transmission;
use App\Models\BodyType;
use App\Models\User;
use App\Models\Image;
use App\Models\Tag;

class ListingSeeder extends Seeder
{
    public function run(): void
    {
        if (Listing::exists()) {
            return;
        }

        $users = User::where('is_admin', false)->get();
        if ($users->isEmpty()) {
            $users = User::factory(3)->create();
        }

        $diesel = Fuel::where('name', 'Diesel')->first();
        $petrol = Fuel::where('name', 'Benzyna')->first();

        $auto = Transmission::where('name', 'Automatyczna')->first();
        $manual = Transmission::where('name', 'Manualna')->first();

        $sedan = BodyType::where('name', 'Sedan')->first();
        $kombi = BodyType::where('name', 'Kombi')->first();
        $suv = BodyType::where('name', 'SUV')->first();
        $hatchback = BodyType::where('name', 'Hatchback')->first();

        $cars = [
            [
                'brand' => 'BMW',
                'model' => 'Seria 3',
                'title' => 'BMW Seria 3 320d',
                'price' => 45000,
                'fuel' => $diesel->id,
                'transmission' => $auto->id,
                'body_type' => $sedan->id,
                'city' => 'Rzeszów',
                'color' => 'Srebrny',
                'year' => 2016,
                'mileage' => 180000,
                'engine_capacity' => 2000,
                'power_hp' => 190,
                'images' => [
                    'bmw1.jpg',
                    'bmw2.jpg',
                ]
            ],
            [
                'brand' => 'Audi',
                'model' => 'A4',
                'title' => 'Audi A4 2.0 TDI',
                'price' => 52000,
                'fuel' => $diesel->id,
                'transmission' => $manual->id,
                'body_type' => $sedan->id,
                'city' => 'Kraków',
                'color' => 'Czarny',
                'year' => 2017,
                'mileage' => 165000,
                'engine_capacity' => 2000,
                'power_hp' => 150,
                'images' => [
                    'audi1.jpg',
                    'audi2.jpg',
                ]
            ],
            [
                'brand' => 'Mercedes-Benz',
                'model' => 'Klasa C',
                'title' => 'Mercedes C220 CDI',
                'price' => 65000,
                'fuel' => $diesel->id,
                'transmission' => $auto->id,
                'body_type' => $sedan->id,
                'city' => 'Warszawa',
                'color' => 'Szary',
                'year' => 2018,
                'mileage' => 140000,
                'engine_capacity' => 2200,
                'power_hp' => 170,
                'images' => [
                    'merc1.jpg',
                    'merc2.jpg',
                ]
            ],
            [
                'brand' => 'Volkswagen',
                'model' => 'Golf',
                'title' => 'Volkswagen Golf VII',
                'price' => 42000,
                'fuel' => $petrol->id,
                'transmission' => $manual->id,
                'body_type' => $hatchback->id,
                'city' => 'Lublin',
                'color' => 'Biały',
                'year' => 2015,
                'mileage' => 190000,
                'engine_capacity' => 1400,
                'power_hp' => 125,
                'images' => [
                    'golf1.jpg',
                    'golf2.jpg',
                ]
            ],
            [
                'brand' => 'Toyota',
                'model' => 'RAV4',
                'title' => 'Toyota RAV4 Hybrid',
                'price' => 98000,
                'fuel' => $petrol->id,
                'transmission' => $auto->id,
                'body_type' => $suv->id,
                'city' => 'Rzeszów',
                'color' => 'Biały',
                'year' => 2020,
                'mileage' => 80000,
                'engine_capacity' => 2500,
                'power_hp' => 218,
                'images' => [
                    'rav4_1.jpg',
                    'rav4_2.jpg',
                ]
            ],
            [
                'brand' => 'Skoda',
                'model' => 'Octavia',
                'title' => 'Skoda Octavia 1.6 TDI',
                'price' => 39000,
                'fuel' => $diesel->id,
                'transmission' => $manual->id,
                'body_type' => $sedan->id,
                'city' => 'Przemyśl',
                'color' => 'Srebrny',
                'year' => 2014,
                'mileage' => 230000,
                'engine_capacity' => 1600,
                'power_hp' => 105,
                'images' => [
                    'octavia1.jpg',
                    'octavia2.jpg',
                ]
            ],
            [
                'brand' => 'Ford',
                'model' => 'Focus',
                'title' => 'Ford Focus 1.5 TDCi',
                'price' => 36000,
                'fuel' => $diesel->id,
                'transmission' => $manual->id,
                'body_type' => $hatchback->id,
                'city' => 'Kraków',
                'color' => 'Niebieski',
                'year' => 2016,
                'mileage' => 175000,
                'engine_capacity' => 1500,
                'power_hp' => 120,
                'images' => ['focus1.jpg', 'focus2.jpg'],
            ],
            [
                'brand' => 'Opel',
                'model' => 'Astra',
                'title' => 'Opel Astra K 1.6 CDTI',
                'price' => 34000,
                'fuel' => $diesel->id,
                'transmission' => $manual->id,
                'body_type' => $hatchback->id,
                'city' => 'Lublin',
                'color' => 'Czarny',
                'year' => 2017,
                'mileage' => 160000,
                'engine_capacity' => 1600,
                'power_hp' => 136,
                'images' => ['astra1.jpg', 'astra2.jpg'],
            ],
            [
                'brand' => 'Volkswagen',
                'model' => 'Passat',
                'title' => 'VW Passat B8 2.0 TDI',
                'price' => 72000,
                'fuel' => $diesel->id,
                'transmission' => $auto->id,
                'body_type' => $sedan->id,
                'city' => 'Warszawa',
                'color' => 'Szary',
                'year' => 2018,
                'mileage' => 140000,
                'engine_capacity' => 2000,
                'power_hp' => 150,
                'images' => ['passat1.jpg', 'passat2.jpg'],
            ],
            [
                'brand' => 'Toyota',
                'model' => 'Corolla',
                'title' => 'Toyota Corolla 1.8 Hybrid',
                'price' => 85000,
                'fuel' => $petrol->id,
                'transmission' => $auto->id,
                'body_type' => $sedan->id,
                'city' => 'Rzeszów',
                'color' => 'Biały',
                'year' => 2020,
                'mileage' => 90000,
                'engine_capacity' => 1800,
                'power_hp' => 122,
                'images' => ['corolla1.jpg', 'corolla2.jpg'],
            ],
            [
                'brand' => 'BMW',
                'model' => 'X5',
                'title' => 'BMW X5 3.0d xDrive',
                'price' => 145000,
                'fuel' => $diesel->id,
                'transmission' => $auto->id,
                'body_type' => $suv->id,
                'city' => 'Kraków',
                'color' => 'Czarny',
                'year' => 2019,
                'mileage' => 110000,
                'engine_capacity' => 3000,
                'power_hp' => 265,
                'images' => ['x5_1.jpg', 'x5_2.jpg'],
            ],
            [
                'brand' => 'Audi',
                'model' => 'A6',
                'title' => 'Audi A6 3.0 TDI Quattro',
                'price' => 135000,
                'fuel' => $diesel->id,
                'transmission' => $auto->id,
                'body_type' => $sedan->id,
                'city' => 'Warszawa',
                'color' => 'Granatowy',
                'year' => 2019,
                'mileage' => 120000,
                'engine_capacity' => 3000,
                'power_hp' => 231,
                'images' => ['a6_1.jpg', 'a6_2.jpg'],
            ],
            [
                'brand' => 'Mercedes-Benz',
                'model' => 'Klasa E',
                'title' => 'Mercedes E220d AMG Line',
                'price' => 155000,
                'fuel' => $diesel->id,
                'transmission' => $auto->id,
                'body_type' => $sedan->id,
                'city' => 'Rzeszów',
                'color' => 'Srebrny',
                'year' => 2020,
                'mileage' => 100000,
                'engine_capacity' => 2000,
                'power_hp' => 194,
                'images' => ['eclass1.jpg', 'eclass2.jpg'],
            ],
            [
                'brand' => 'Skoda',
                'model' => 'Superb',
                'title' => 'Skoda Superb 2.0 TDI DSG',
                'price' => 98000,
                'fuel' => $diesel->id,
                'transmission' => $auto->id,
                'body_type' => $kombi->id,
                'city' => 'Lublin',
                'color' => 'Czarny',
                'year' => 2019,
                'mileage' => 130000,
                'engine_capacity' => 2000,
                'power_hp' => 190,
                'images' => ['superb1.jpg', 'superb2.jpg'],
            ],
            [
                'brand' => 'Hyundai',
                'model' => 'Tucson',
                'title' => 'Hyundai Tucson 1.6 T-GDI',
                'price' => 105000,
                'fuel' => $petrol->id,
                'transmission' => $auto->id,
                'body_type' => $suv->id,
                'city' => 'Kraków',
                'color' => 'Biały',
                'year' => 2021,
                'mileage' => 60000,
                'engine_capacity' => 1600,
                'power_hp' => 177,
                'images' => ['tucson1.jpg', 'tucson2.jpg'],
            ],
            [
                'brand' => 'Kia',
                'model' => 'Sportage',
                'title' => 'Kia Sportage 1.6 CRDi',
                'price' => 89000,
                'fuel' => $diesel->id,
                'transmission' => $auto->id,
                'body_type' => $suv->id,
                'city' => 'Warszawa',
                'color' => 'Szary',
                'year' => 2019,
                'mileage' => 120000,
                'engine_capacity' => 1600,
                'power_hp' => 136,
                'images' => ['sportage1.jpg', 'sportage2.jpg'],
            ],
            [
                'brand' => 'Nissan',
                'model' => 'Qashqai',
                'title' => 'Nissan Qashqai 1.3 DIG-T',
                'price' => 92000,
                'fuel' => $petrol->id,
                'transmission' => $auto->id,
                'body_type' => $suv->id,
                'city' => 'Rzeszów',
                'color' => 'Czerwony',
                'year' => 2020,
                'mileage' => 85000,
                'engine_capacity' => 1300,
                'power_hp' => 140,
                'images' => ['qashqai1.jpg', 'qashqai2.jpg'],
            ],
            [
                'brand' => 'Mazda',
                'model' => 'CX-5',
                'title' => 'Mazda CX-5 2.2 Skyactiv-D',
                'price' => 110000,
                'fuel' => $diesel->id,
                'transmission' => $manual->id,
                'body_type' => $suv->id,
                'city' => 'Lublin',
                'color' => 'Czerwony',
                'year' => 2020,
                'mileage' => 95000,
                'engine_capacity' => 2200,
                'power_hp' => 175,
                'images' => ['cx5_1.jpg', 'cx5_2.jpg'],
            ],
            [
                'brand' => 'Volvo',
                'model' => 'XC60',
                'title' => 'Volvo XC60 D4 AWD',
                'price' => 140000,
                'fuel' => $diesel->id,
                'transmission' => $auto->id,
                'body_type' => $suv->id,
                'city' => 'Warszawa',
                'color' => 'Biały',
                'year' => 2021,
                'mileage' => 80000,
                'engine_capacity' => 2000,
                'power_hp' => 190,
                'images' => ['xc60_1.jpg', 'xc60_2.jpg'],
            ],
            [
                'brand' => 'Honda',
                'model' => 'Civic',
                'title' => 'Honda Civic 1.5 VTEC Turbo',
                'price' => 99000,
                'fuel' => $petrol->id,
                'transmission' => $manual->id,
                'body_type' => $hatchback->id,
                'city' => 'Kraków',
                'color' => 'Niebieski',
                'year' => 2019,
                'mileage' => 110000,
                'engine_capacity' => 1500,
                'power_hp' => 182,
                'images' => ['civic1.jpg', 'civic2.jpg'],
            ],
            [
                'brand' => 'Peugeot',
                'model' => '3008',
                'title' => 'Peugeot 3008 1.5 BlueHDi',
                'price' => 115000,
                'fuel' => $diesel->id,
                'transmission' => $auto->id,
                'body_type' => $suv->id,
                'city' => 'Rzeszów',
                'color' => 'Szary',
                'year' => 2021,
                'mileage' => 70000,
                'engine_capacity' => 1500,
                'power_hp' => 130,
                'images' => ['3008_1.jpg', '3008_2.jpg'],
            ],
            [
                'brand' => 'Seat',
                'model' => 'Leon',
                'title' => 'Seat Leon 2.0 TDI FR',
                'price' => 78000,
                'fuel' => $diesel->id,
                'transmission' => $manual->id,
                'body_type' => $hatchback->id,
                'city' => 'Lublin',
                'color' => 'Czarny',
                'year' => 2018,
                'mileage' => 140000,
                'engine_capacity' => 2000,
                'power_hp' => 150,
                'images' => ['leon1.jpg', 'leon2.jpg'],
            ],
            [
                'brand' => 'Fiat',
                'model' => '500',
                'title' => 'Fiat 500 1.2 Lounge',
                'price' => 45000,
                'fuel' => $petrol->id,
                'transmission' => $manual->id,
                'body_type' => $hatchback->id,
                'city' => 'Warszawa',
                'color' => 'Biały',
                'year' => 2017,
                'mileage' => 90000,
                'engine_capacity' => 1200,
                'power_hp' => 69,
                'images' => ['500_1.jpg', '500_2.jpg'],
            ],
        ];

        $coords = [
            'Rzeszów' => ['lat' => 50.0412, 'lng' => 21.9990],
            'Kraków' => ['lat' => 50.0647, 'lng' => 19.9450],
            'Warszawa' => ['lat' => 52.2297, 'lng' => 21.0122],
            'Lublin' => ['lat' => 51.2465, 'lng' => 22.5684],
            'Przemyśl' => ['lat' => 49.7840, 'lng' => 22.7870],
        ];

        foreach ($cars as $index => $car) {

            $user = $users[$index % $users->count()];

            $brand = Brand::where('name', $car['brand'])->first();
            $model = CarModel::where('name', $car['model'])->first();
            if (!$brand || !$model) {
                continue;
            }

            $c = $coords[$car['city']] ?? [
                'lat' => 52.0693,
                'lng' => 19.4803
            ];

            $listing = Listing::create([
                'user_id' => $user->id,
                'brand_id' => $brand->id,
                'model_id' => $model->id,
                'fuel_id' => $car['fuel'],
                'transmission_id' => $car['transmission'],
                'body_type_id' => $car['body_type'],
                'title' => $car['title'],
                'description' => 'Auto w bardzo dobrym stanie technicznym i wizualnym.',
                'price' => $car['price'],
                'status' => 'active',
                'city' => $car['city'],
                'latitude' => $c['lat'],
                'longitude' => $c['lng'],
                'color' => $car['color'],
                'year' => $car['year'],
                'mileage' => $car['mileage'],
                'engine_capacity' => $car['engine_capacity'],
                'power_hp' => $car['power_hp'],
            ]);

            $imageIds = Image::whereIn(
                'original_name',
                $car['images']
            )->pluck('id');

            $listing->images()->attach($imageIds);

            $tagIds = Tag::inRandomOrder()
                ->take(rand(2, 4))
                ->pluck('id')
                ->toArray();

            $listing->tags()->sync($tagIds);
        }
    }
}

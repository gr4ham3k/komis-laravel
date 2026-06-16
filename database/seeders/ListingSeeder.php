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
        $user = User::factory()->create([
            'name' => 'Jan',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
            'is_banned' => false
        ]);

        $diesel = Fuel::where('name', 'Diesel')->first();
        $petrol = Fuel::where('name', 'Benzyna')->first();

        $auto = Transmission::where('name', 'Automatyczna')->first();
        $manual = Transmission::where('name', 'Manualna')->first();

        $sedan = BodyType::where('name', 'Sedan')->first();
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
        ];

        foreach ($cars as $car) {
            $brand = Brand::where('name', $car['brand'])->first();
            $model = CarModel::where('name', $car['model'])->first();

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

            $tagIds = Tag::inRandomOrder()->take(rand(2, 4))->pluck('id')->toArray();
            $listing->tags()->sync($tagIds);
        }
    }
}

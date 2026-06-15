<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Listing;
use App\Models\Image;
use App\Models\User;
use App\Models\CarModel;
use App\Models\Fuel;
use App\Models\Transmission;
use App\Models\BodyType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AddRealisticCarsSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first user or create one
        $user = User::first() ?? User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);


        
        // Ensure storage directory exists
        Storage::disk('public')->makeDirectory('listings');

        // Dostępne tagi z bazy
        $availableTags = \App\Models\Tag::pluck('id', 'name')->toArray();

        $cars = [
            [
                'brand' => 'Toyota', 'model' => 'Corolla', 'fuel' => 'Hybryda', 'transmission' => 'Automatyczna', 'body' => 'Kombi',
                'title' => 'Toyota Corolla 1.8 Hybrid Comfort', 'price' => 95000, 'year' => 2020, 'mileage' => 45000, 'engine' => 1798, 'power' => 122, 'color' => 'Srebrny', 'city' => 'Warszawa', 'lat' => 52.2297, 'lng' => 21.0122, 
                'image' => 'toyota_corolla.png', 'tags' => ['Klimatyzacja', 'Kamera cofania', 'Bezwypadkowy']
            ],
            [
                'brand' => 'Toyota', 'model' => 'Yaris', 'fuel' => 'Benzyna', 'transmission' => 'Manualna', 'body' => 'Hatchback',
                'title' => 'Toyota Yaris 1.5 VVT-i', 'price' => 55000, 'year' => 2019, 'mileage' => 60000, 'engine' => 1496, 'power' => 111, 'color' => 'Czerwony', 'city' => 'Kraków', 'lat' => 50.0647, 'lng' => 19.9450, 
                'image' => 'toyota_yaris.png', 'tags' => ['Manual', 'Zarejestrowany w Polsce', 'Pierwszy właściciel']
            ],
            [
                'brand' => 'BMW', 'model' => 'Seria 3', 'fuel' => 'Diesel', 'transmission' => 'Automatyczna', 'body' => 'Sedan',
                'title' => 'BMW Seria 3 320d M-Sport', 'price' => 125000, 'year' => 2018, 'mileage' => 110000, 'engine' => 1995, 'power' => 190, 'color' => 'Czarny', 'city' => 'Poznań', 'lat' => 52.4064, 'lng' => 16.9252, 
                'image' => 'bmw_3.png', 'tags' => ['Automat', 'Skórzana tapicerka', 'Nawigacja', 'Podgrzewane fotele']
            ],
            [
                'brand' => 'BMW', 'model' => 'Seria 5', 'fuel' => 'Benzyna', 'transmission' => 'Automatyczna', 'body' => 'Sedan',
                'title' => 'BMW Seria 5 530i xDrive', 'price' => 180000, 'year' => 2021, 'mileage' => 35000, 'engine' => 1998, 'power' => 252, 'color' => 'Granatowy', 'city' => 'Wrocław', 'lat' => 51.1079, 'lng' => 17.0385, 
                'image' => 'bmw_5.png', 'tags' => ['Automat', 'Skórzana tapicerka', 'Nawigacja', 'Faktura VAT']
            ],
            [
                'brand' => 'Audi', 'model' => 'A3', 'fuel' => 'Benzyna', 'transmission' => 'Automatyczna', 'body' => 'Hatchback',
                'title' => 'Audi A3 Sportback 35 TFSI', 'price' => 115000, 'year' => 2020, 'mileage' => 40000, 'engine' => 1498, 'power' => 150, 'color' => 'Biały', 'city' => 'Gdańsk', 'lat' => 54.3520, 'lng' => 18.6466, 
                'image' => 'audi_a3.png', 'tags' => ['Klimatyzacja', 'Serwisowany w ASO', 'Bezwypadkowy']
            ],
            [
                'brand' => 'Audi', 'model' => 'A4', 'fuel' => 'Diesel', 'transmission' => 'Automatyczna', 'body' => 'Kombi',
                'title' => 'Audi A4 Avant 40 TDI quattro', 'price' => 145000, 'year' => 2019, 'mileage' => 85000, 'engine' => 1968, 'power' => 190, 'color' => 'Szary', 'city' => 'Szczecin', 'lat' => 53.4285, 'lng' => 14.5528, 
                'image' => 'audi_a4.png', 'tags' => ['Automat', 'Nawigacja', 'Kamera cofania']
            ],
            [
                'brand' => 'Mercedes-Benz', 'model' => 'Klasa C', 'fuel' => 'Benzyna', 'transmission' => 'Automatyczna', 'body' => 'Sedan',
                'title' => 'Mercedes-Benz C 200 AMG Line', 'price' => 165000, 'year' => 2021, 'mileage' => 25000, 'engine' => 1496, 'power' => 204, 'color' => 'Czarny', 'city' => 'Łódź', 'lat' => 51.7592, 'lng' => 19.4560, 
                'image' => 'mercedes_cclass.png', 'tags' => ['Skórzana tapicerka', 'Garażowany', 'Bezwypadkowy']
            ],
            [
                'brand' => 'Toyota', 'model' => 'Corolla', 'fuel' => 'Benzyna', 'transmission' => 'Manualna', 'body' => 'Sedan',
                'title' => 'Toyota Corolla 1.5 VVT-i', 'price' => 75000, 'year' => 2021, 'mileage' => 30000, 'engine' => 1490, 'power' => 125, 'color' => 'Biały', 'city' => 'Lublin', 'lat' => 51.2465, 'lng' => 22.5684, 
                'image' => 'toyota_corolla_white.png', 'tags' => ['Manual', 'Klimatyzacja']
            ]
        ];

        foreach ($cars as $carData) {
            $model = CarModel::where('name', $carData['model'])->first();
            $fuel = Fuel::where('name', $carData['fuel'])->first() ?? Fuel::first();
            $transmission = Transmission::where('name', $carData['transmission'])->first() ?? Transmission::first();
            $body = BodyType::where('name', $carData['body'])->first() ?? BodyType::first();

            if (!$model) continue;

            $listing = Listing::updateOrCreate(
                ['title' => $carData['title']],
                [
                    'user_id' => $user->id,
                    'brand_id' => $model->brand_id,
                    'model_id' => $model->id,
                    'fuel_id' => $fuel->id,
                    'transmission_id' => $transmission->id,
                    'body_type_id' => $body->id,
                    'description' => 'Świetny samochód, sprawdzony i gotowy do jazdy. Pojazd utrzymany w idealnym stanie wizualnym i technicznym. Zapraszam do kontaktu i na jazdę próbną.',
                    'price' => $carData['price'],
                    'status' => 'active',
                    'city' => $carData['city'],
                    'color' => $carData['color'],
                    'year' => $carData['year'],
                    'mileage' => $carData['mileage'],
                    'engine_capacity' => $carData['engine'],
                    'power_hp' => $carData['power'],
                    'latitude' => $carData['lat'],
                    'longitude' => $carData['lng'],
                ]
            );

            // Tags
            $tagIds = [];
            foreach ($carData['tags'] as $tagName) {
                if (isset($availableTags[$tagName])) {
                    $tagIds[] = $availableTags[$tagName];
                }
            }
            if (!empty($tagIds)) {
                $listing->tags()->sync($tagIds);
            }

            // Static Image
            if ($listing->images()->count() === 0) {
                $image = Image::create([
                    'original_name' => $carData['image'],
                    'file_name' => $carData['image'],
                    'file_type' => 'image/png',
                ]);
                $listing->images()->attach($image->id, ['sort_order' => 1]);
                $this->command->info("Added static image for " . $carData['title']);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceReview;
use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('email', 'test@example.com')->first();

        if (!$owner) {
            $owner = User::factory()->create([
                'name' => 'Mechanik Kowalski',
                'email' => 'mechanik@example.com',
            ]);
        }

        $reviewers = User::whereNotIn('id', [$owner->id])->take(3)->get();
        if ($reviewers->count() < 3) {
            $reviewers = User::factory(3 - $reviewers->count())->create();
        }

        $services = [
            [
                'title' => 'Wulkanizacja 24h',
                'description' => 'Szybka wymiana opon, wyważanie, naprawa przebić. Dojazd do klienta gratis.',
                'price' => 150.00,
                'city' => 'Warszawa',
                'image' => 'wulkanizacja.jpg',
            ],
            [
                'title' => 'Diagnostyka komputerowa',
                'description' => 'Pełna diagnostyka silnika, ABS, ESP. Odczyt i kasowanie błędów.',
                'price' => 200.00,
                'city' => 'Kraków',
                'image' => 'diagnostyka.jpg',
            ],
            [
                'title' => 'Detailing wnętrza',
                'description' => 'Czyszczenie parą, pranie tapicerki, odświeżenie skóry.',
                'price' => 350.00,
                'city' => 'Wrocław',
                'image' => 'detailing.jpg',
            ],
            [
                'title' => 'Wymiana oleju',
                'description' => 'Olej + filtr + sprawdzenie układu hamulcowego.',
                'price' => 180.00,
                'city' => 'Poznań',
                'image' => 'olej.jpg',
            ],
        ];

        foreach ($services as $serviceData) {
            $service = Service::create([
                'user_id' => $owner->id,
                'title' => $serviceData['title'],
                'description' => $serviceData['description'],
                'price' => $serviceData['price'],
                'city' => $serviceData['city'],
                'status' => 'active',
                'views_count' => rand(0, 100),
            ]);

            $sourcePath = database_path('seed-images/' . $serviceData['image']);
            if (file_exists($sourcePath)) {
                $uuid = (string) Str::uuid() . '.jpg';
                $storagePath = 'services/' . $uuid;
                Storage::disk('public')->put($storagePath, file_get_contents($sourcePath));

                $srvImage = Image::create([
                    'original_name' => $serviceData['image'],
                    'file_name' => $storagePath,
                    'file_type' => 'image/jpeg',
                ]);
                $service->images()->attach($srvImage->id, ['sort_order' => 0]);
            }

            $reviews = [
                ['rating' => 5, 'comment' => 'Świetna obsługa, polecam!'],
                ['rating' => 4, 'comment' => 'Dobrze, ale trochę drogo.'],
                ['rating' => 5, 'comment' => 'Szybko i profesjonalnie.'],
            ];

            foreach ($reviews as $i => $reviewData) {
                ServiceReview::create([
                    'service_id' => $service->id,
                    'user_id' => $reviewers[$i]->id,
                    'rating' => $reviewData['rating'],
                    'comment' => $reviewData['comment'],
                ]);
            }
        }
    }
}

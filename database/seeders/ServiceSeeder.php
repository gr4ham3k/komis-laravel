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
        $owner = User::firstOrCreate(
            ['email' => 'mechanik@example.com'],
            [
                'name' => 'Mechanik Kowalski',
                'password' => bcrypt('password'),
            ]
        );

        $reviewers = User::whereNotIn('id', [$owner->id])->take(3)->get()->values();
        if ($reviewers->count() < 3) {
            $reviewers = $reviewers
                ->merge(User::factory(3 - $reviewers->count())->create())
                ->values();
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
            $service = Service::firstOrCreate(
                [
                    'title' => $serviceData['title'],
                    'city' => $serviceData['city'],
                ],
                [
                    'user_id' => $owner->id,
                    'description' => $serviceData['description'],
                    'price' => $serviceData['price'],
                    'status' => 'active',
                    'views_count' => rand(0, 100),
                ]
            );

            $sourcePath = database_path('seed-images/' . $serviceData['image']);
            if (file_exists($sourcePath)) {
                $serviceImage = $service->images()
                    ->where('original_name', $serviceData['image'])
                    ->first();

                if (!$serviceImage) {
                    $serviceImage = Image::where('original_name', $serviceData['image'])
                        ->where('file_name', 'like', 'services/%')
                        ->first();
                }

                if (!$serviceImage) {
                    $uuid = (string) Str::uuid() . '.jpg';
                    $storagePath = 'services/' . $uuid;

                    $serviceImage = Image::create([
                        'original_name' => $serviceData['image'],
                        'file_name' => $storagePath,
                        'file_type' => 'image/jpeg',
                    ]);
                }

                Storage::disk('public')->put(
                    $serviceImage->file_name,
                    file_get_contents($sourcePath)
                );

                if (!$service->images()->whereKey($serviceImage->id)->exists()) {
                    $service->images()->attach($serviceImage->id, ['sort_order' => 0]);
                }
            }

            $reviews = [
                ['rating' => 5, 'comment' => 'Świetna obsługa, polecam!'],
                ['rating' => 4, 'comment' => 'Dobrze, ale trochę drogo.'],
                ['rating' => 5, 'comment' => 'Szybko i profesjonalnie.'],
            ];

            if (!$service->reviews()->exists()) {
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
}

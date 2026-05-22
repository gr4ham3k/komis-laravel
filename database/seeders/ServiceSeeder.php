<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceReview;
use App\Models\User;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // Pobierz pierwszego użytkownika (lub stwórz)
        $user = User::first();

        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Mechanik Kowalski',
                'email' => 'mechanik@example.com'
            ]);
        }

        // Przykładowe serwisy
        $services = [
            [
                'title' => 'Wulkanizacja 24h',
                'description' => 'Szybka wymiana opon, wyważanie, naprawa przebić. Dojazd do klienta gratis.',
                'price' => 150.00,
                'city' => 'Warszawa'
            ],
            [
                'title' => 'Diagnostyka komputerowa',
                'description' => 'Pełna diagnostyka silnika, ABS, ESP. Odczyt i kasowanie błędów.',
                'price' => 200.00,
                'city' => 'Kraków'
            ],
            [
                'title' => 'Detailing wnętrza',
                'description' => 'Czyszczenie parą, pranie tapicerki, odświeżenie skóry.',
                'price' => 350.00,
                'city' => 'Wrocław'
            ],
            [
                'title' => 'Wymiana oleju',
                'description' => 'Olej + filtr + sprawdzenie układu hamulcowego.',
                'price' => 180.00,
                'city' => 'Poznań'
            ]
        ];

        foreach ($services as $serviceData) {
            $service = Service::create([
                'user_id' => $user->id,
                'title' => $serviceData['title'],
                'description' => $serviceData['description'],
                'price' => $serviceData['price'],
                'city' => $serviceData['city'],
                'status' => 'active',
                'views_count' => rand(0, 100)
            ]);

            // Dodaj opinie do każdego serwisu
            $reviews = [
                ['rating' => 5, 'comment' => 'Świetna obsługa, polecam!'],
                ['rating' => 4, 'comment' => 'Dobrze, ale trochę drogo.'],
                ['rating' => 5, 'comment' => 'Szybko i profesjonalnie.'],
            ];

            foreach ($reviews as $reviewData) {
                ServiceReview::create([
                    'service_id' => $service->id,
                    'user_id' => $user->id,
                    'rating' => $reviewData['rating'],
                    'comment' => $reviewData['comment']
                ]);
            }
        }
    }
}
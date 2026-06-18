<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Admin', 'email' => 'admin@admin.com', 'password' => 'admin', 'is_admin' => true],
            ['name' => 'Jan', 'email' => 'jan@example.com'],
            ['name' => 'Kamil', 'email' => 'kamil@example.com'],
            ['name' => 'Jakub', 'email' => 'jakub@example.com'],
            ['name' => 'Wojtek', 'email' => 'wojtek@example.com'],
            ['name' => 'Adam', 'email' => 'adam@example.com'],
            ['name' => 'Adrian', 'email' => 'adrian@example.com'],
            ['name' => 'Hubert', 'email' => 'hubert@example.com'],
            ['name' => 'Filip', 'email' => 'filip@example.com'],
            ['name' => 'Mateusz', 'email' => 'mateusz@example.com'],
            ['name' => 'Tomasz', 'email' => 'tomasz@example.com'],
        ];

        foreach ($users as $user) {
            $createdUser = User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($user['password'] ?? 'password'),
                    'is_admin' => $user['is_admin'] ?? false,
                    'is_banned' => false,
                ]
            );

            if (($user['is_admin'] ?? false) && (!$createdUser->is_admin || $createdUser->is_banned)) {
                $createdUser->forceFill([
                    'is_admin' => true,
                    'is_banned' => false,
                ])->save();
            }
        }

        $this->call([
            ImageSeeder::class,
            CarDataSeeder::class,
            TagSeeder::class,
            ListingSeeder::class,
            ServiceSeeder::class
        ]);
    }
}

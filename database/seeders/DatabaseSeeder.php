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
        // User::factory(10)->create();

        $this->call([
            ImageSeeder::class,
            CarDataSeeder::class,
            ListingSeeder::class,
            TagSeeder::class,
            ServiceSeeder::class
        ]);

        User::query()->updateOrCreate(
            ['email' => 'admin@komis.test'],
            [
                'name' => 'Admin',
                'email' => 'admin@komis.test',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'is_banned' => false,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'user@komis.test'],
            [
                'name' => 'Uzytkownik',
                'email' => 'user@komis.test',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'is_banned' => false,
            ]
        );
    }
}

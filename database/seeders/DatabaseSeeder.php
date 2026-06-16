<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@komis.test',
            'password' => bcrypt('admin'),
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Kamil',
            'email' => 'second@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory(8)->create([
            'password' => bcrypt('password'),
        ]);

        $this->call([
            ImageSeeder::class,
            CarDataSeeder::class,
            TagSeeder::class,
            ListingSeeder::class,
            ServiceSeeder::class
        ]);

    }
}

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
        if (User::count() > 0) {
            return;
        }

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Jan',
            'email' => 'jan@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Kamil',
            'email' => 'kamil@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Jakub',
            'email' => 'jakub@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Wojtek',
            'email' => 'wojtek@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Adam',
            'email' => 'adam@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Adrian',
            'email' => 'adrian@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Hubert',
            'email' => 'hubert@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Filip',
            'email' => 'filip@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Mateusz',
            'email' => 'mateusz@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Tomasz',
            'email' => 'tomasz@example.com',
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

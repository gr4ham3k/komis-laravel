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

class ListingSeeder extends Seeder
{
    public function run(): void
    {

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
            'is_banned' => false
        ]);

        $bmw = Brand::where('name', 'BMW')->first();

        $bmw3 = CarModel::where('name', 'Seria 3')->first();

        $diesel = Fuel::where('name', 'Diesel')->first();
        $auto = Transmission::where('name', 'Automatyczna')->first();
        $sedan = BodyType::where('name', 'Sedan')->first();

        $listing = Listing::create([
            'user_id' => $user->id,
            'brand_id' => $bmw->id,
            'model_id' => $bmw3->id,
            'fuel_id' => $diesel->id,
            'transmission_id' => $auto->id,
            'body_type_id' => $sedan->id,
            'title' => 'BMW Seria 3 320d',
            'description' => 'Zadbane BMW, serwisowane, stan bardzo dobry.',
            'price' => 45000,
            'status' => 'inactive',
            'city' => 'Rzeszów',
            'color' => 'Srebrny',
            'year' => 2016,
            'mileage' => 180000,
            'engine_capacity' => 2000,
            'power_hp' => 190,
        ]);



    }
}

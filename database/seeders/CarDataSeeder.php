<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Fuel;
use App\Models\Transmission;
use App\Models\BodyType;

class CarDataSeeder extends Seeder
{
    public function run(): void
    {
        $toyota = Brand::create(['name' => 'Toyota']);
        $bmw = Brand::create(['name' => 'BMW']);
        $audi = Brand::create(['name' => 'Audi']);

        CarModel::create(['brand_id' => $toyota->id, 'name' => 'Corolla']);
        CarModel::create(['brand_id' => $toyota->id, 'name' => 'Yaris']);

        CarModel::create(['brand_id' => $bmw->id, 'name' => 'Seria 3']);
        CarModel::create(['brand_id' => $bmw->id, 'name' => 'Seria 5']);

        CarModel::create(['brand_id' => $audi->id, 'name' => 'A3']);
        CarModel::create(['brand_id' => $audi->id, 'name' => 'A4']);

        Fuel::insert([
            ['name' => 'Benzyna', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Diesel', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hybryda', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Transmission::insert([
            ['name' => 'Manuala', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Automatyczna', 'created_at' => now(), 'updated_at' => now()],
        ]);

        BodyType::insert([
            ['name' => 'Sedan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hatchback', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SUV', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Coupe', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

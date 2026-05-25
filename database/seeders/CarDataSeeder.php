<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\BodyType;
use App\Models\CarModel;
use App\Models\Fuel;
use App\Models\Transmission;
use Illuminate\Database\Seeder;

class CarDataSeeder extends Seeder
{
    public function run(): void
    {
        $toyota = Brand::query()->updateOrCreate(['name' => 'Toyota'], ['name' => 'Toyota']);
        $bmw = Brand::query()->updateOrCreate(['name' => 'BMW'], ['name' => 'BMW']);
        $audi = Brand::query()->updateOrCreate(['name' => 'Audi'], ['name' => 'Audi']);

        CarModel::query()->updateOrCreate(['brand_id' => $toyota->id, 'name' => 'Corolla'], ['name' => 'Corolla']);
        CarModel::query()->updateOrCreate(['brand_id' => $toyota->id, 'name' => 'Yaris'], ['name' => 'Yaris']);

        CarModel::query()->updateOrCreate(['brand_id' => $bmw->id, 'name' => 'Seria 3'], ['name' => 'Seria 3']);
        CarModel::query()->updateOrCreate(['brand_id' => $bmw->id, 'name' => 'Seria 5'], ['name' => 'Seria 5']);

        CarModel::query()->updateOrCreate(['brand_id' => $audi->id, 'name' => 'A3'], ['name' => 'A3']);
        CarModel::query()->updateOrCreate(['brand_id' => $audi->id, 'name' => 'A4'], ['name' => 'A4']);

        foreach (['Benzyna', 'Diesel', 'Hybryda'] as $fuelName) {
            Fuel::query()->updateOrCreate(['name' => $fuelName], ['name' => $fuelName]);
        }

        foreach (['Manualna', 'Automatyczna'] as $transmissionName) {
            Transmission::query()->updateOrCreate(['name' => $transmissionName], ['name' => $transmissionName]);
        }

        foreach (['Sedan', 'Hatchback', 'SUV', 'Coupe'] as $bodyTypeName) {
            BodyType::query()->updateOrCreate(['name' => $bodyTypeName], ['name' => $bodyTypeName]);
        }
    }
}

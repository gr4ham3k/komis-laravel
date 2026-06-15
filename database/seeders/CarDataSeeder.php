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
        $brands = [
            'Toyota' => ['Corolla', 'Yaris', 'Camry', 'RAV4', 'Auris', 'C-HR', 'Hilux', 'Supra', 'Land Cruiser', 'Prius'],
            'BMW' => ['Seria 3', 'Seria 5', 'Seria 1', 'Seria 7', 'X1', 'X3', 'X5', 'X6', 'Z4', 'i4'],
            'Audi' => ['A3', 'A4', 'A6', 'A8', 'Q3', 'Q5', 'Q7', 'Q8', 'TT', 'e-tron'],
            'Mercedes-Benz' => ['Klasa A', 'Klasa C', 'Klasa E', 'Klasa S', 'GLA', 'GLC', 'GLE', 'GLS', 'Klasa CLS', 'Klasa G'],
            'Volkswagen' => ['Golf', 'Passat', 'Tiguan', 'Polo', 'T-Roc', 'ID.3', 'ID.4', 'Touran', 'Arteon', 'Transporter'],
            'Ford' => ['Focus', 'Mondeo', 'Kuga', 'Fiesta', 'Mustang', 'Puma', 'Explorer', 'Ranger', 'Galaxy', 'S-Max'],
            'Opel' => ['Astra', 'Insignia', 'Corsa', 'Mokka', 'Grandland', 'Crossland', 'Combo', 'Vivaro', 'Zafira', 'Adam'],
            'Hyundai' => ['i30', 'Tucson', 'Santa Fe', 'Kona', 'i10', 'i20', 'Elantra', 'IONIQ 5', 'Bayon', 'Staria'],
            'Kia' => ['Ceed', 'Sportage', 'Sorento', 'Stonic', 'Picanto', 'Rio', 'Niro', 'EV6', 'ProCeed', 'Soul'],
            'Skoda' => ['Octavia', 'Superb', 'Fabia', 'Kodiaq', 'Karoq', 'Scala', 'Kamiq', 'Enyaq', 'Citigo', 'Rapid'],
            'Renault' => ['Clio', 'Megane', 'Captur', 'Arkana', 'Talisman', 'Kadjar', 'Austral', 'Zoe', 'Master', 'Trafic'],
            'Peugeot' => ['308', '508', '3008', '5008', '208', '2008', 'Rifter', 'Traveller', 'Partner', 'e-208'],
            'Citroen' => ['C3', 'C4', 'C5 Aircross', 'Berlingo', 'C1', 'C5 X', 'SpaceTourer', 'Jumpy', 'Ami', 'DS 7'],
            'Mazda' => ['Mazda3', 'Mazda6', 'CX-5', 'CX-30', 'MX-5', 'CX-3', 'CX-60', 'MX-30', 'Mazda2', 'CX-9'],
            'Honda' => ['Civic', 'CR-V', 'HR-V', 'Jazz', 'Accord', 'NSX', 'e', 'Pilot', 'S2000', 'Legend'],
            'Nissan' => ['Qashqai', 'Juke', 'X-Trail', 'Micra', 'Leaf', 'Navara', 'Pathfinder', 'GT-R', 'Ariya', 'Note'],
            'Volvo' => ['XC40', 'XC60', 'XC90', 'S60', 'V60', 'S90', 'V90', 'C40', 'EX30', 'Polestar 2'],
            'Fiat' => ['500', 'Panda', 'Tipo', 'Doblo', 'Ducato', '500X', '500L', 'Punto', 'Talento', 'Fullback'],
            'Suzuki' => ['Swift', 'Vitara', 'S-Cross', 'Ignis', 'Jimny', 'Across', 'Swace', 'Baleno', 'Celerio', 'SX4'],
            'Seat' => ['Leon', 'Arona', 'Ateca', 'Ibiza', 'Tarraco', 'Alhambra', 'Mii', 'Toledo', 'Exeo', 'Malaga'],
            'Mitsubishi' => ['Outlander', 'Eclipse Cross', 'ASX', 'L200', 'Space Star', 'Colt', 'Pajero', 'Carisma', 'Lancer', 'i-MiEV'],
            'Porsche' => ['911', 'Cayenne', 'Macan', 'Panamera', 'Taycan', 'Cayman', 'Boxster', '918 Spyder', 'Carrera GT', '356'],
            'Land Rover' => ['Range Rover', 'Range Rover Sport', 'Range Rover Evoque', 'Discovery', 'Discovery Sport', 'Defender', 'Freelander', 'Velar'],
            'Jeep' => ['Wrangler', 'Cherokee', 'Grand Cherokee', 'Renegade', 'Compass', 'Avenger', 'Gladiator', 'Wagoneer'],
            'Alfa Romeo' => ['Giulia', 'Stelvio', 'Tonale', 'Giulietta', 'MiTo', '4C', 'Spider', 'GTV', '159', 'Brera'],
        ];

        foreach ($brands as $brandName => $models) {
            $brand = Brand::firstOrCreate(['name' => $brandName]);
            foreach ($models as $modelName) {
                CarModel::firstOrCreate([
                    'brand_id' => $brand->id,
                    'name' => $modelName,
                ]);
            }
        }

        if (Fuel::count() === 0) {
            Fuel::insert([
                ['name' => 'Benzyna', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Diesel', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Hybryda', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Elektryczny', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'LPG', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if (Transmission::count() === 0) {
            Transmission::insert([
                ['name' => 'Manualna', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Automatyczna', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if (BodyType::count() === 0) {
            BodyType::insert([
                ['name' => 'Sedan', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Hatchback', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'SUV', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Coupe', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Kombi', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Kabriolet', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Minivan', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }
}

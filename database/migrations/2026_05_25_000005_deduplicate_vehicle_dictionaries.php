<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->normalizeTransmissions();
        $this->deduplicateByName('brands', 'car_models', 'brand_id');
        $this->deduplicateByName('fuels', 'listings', 'fuel_id');
        $this->deduplicateByName('transmissions', 'listings', 'transmission_id');
        $this->deduplicateByName('body_types', 'listings', 'body_type_id');
        $this->deduplicateCarModels();

        Schema::table('brands', function (Blueprint $table): void {
            $table->unique('name');
        });

        Schema::table('fuels', function (Blueprint $table): void {
            $table->unique('name');
        });

        Schema::table('transmissions', function (Blueprint $table): void {
            $table->unique('name');
        });

        Schema::table('body_types', function (Blueprint $table): void {
            $table->unique('name');
        });

        Schema::table('car_models', function (Blueprint $table): void {
            $table->unique(['brand_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::table('car_models', function (Blueprint $table): void {
            $table->dropUnique(['brand_id', 'name']);
        });

        Schema::table('body_types', function (Blueprint $table): void {
            $table->dropUnique(['name']);
        });

        Schema::table('transmissions', function (Blueprint $table): void {
            $table->dropUnique(['name']);
        });

        Schema::table('fuels', function (Blueprint $table): void {
            $table->dropUnique(['name']);
        });

        Schema::table('brands', function (Blueprint $table): void {
            $table->dropUnique(['name']);
        });
    }

    private function normalizeTransmissions(): void
    {
        DB::table('transmissions')
            ->where('name', 'Manuala')
            ->update(['name' => 'Manualna']);
    }

    private function deduplicateByName(string $table, string $foreignTable, string $foreignKey): void
    {
        $duplicateNames = DB::table($table)
            ->select('name')
            ->groupBy('name')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('name');

        foreach ($duplicateNames as $name) {
            $ids = DB::table($table)
                ->where('name', $name)
                ->orderBy('id')
                ->pluck('id')
                ->values();

            if ($ids->count() < 2) {
                continue;
            }

            $keepId = (int) $ids->first();
            $duplicateIds = $ids->slice(1)->map(fn ($id) => (int) $id)->values();

            DB::table($foreignTable)
                ->whereIn($foreignKey, $duplicateIds)
                ->update([$foreignKey => $keepId]);

            DB::table($table)->whereIn('id', $duplicateIds)->delete();
        }
    }

    private function deduplicateCarModels(): void
    {
        $duplicates = DB::table('car_models')
            ->select('brand_id', 'name')
            ->groupBy('brand_id', 'name')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            $ids = DB::table('car_models')
                ->where('brand_id', $duplicate->brand_id)
                ->where('name', $duplicate->name)
                ->orderBy('id')
                ->pluck('id')
                ->values();

            if ($ids->count() < 2) {
                continue;
            }

            $keepId = (int) $ids->first();
            $duplicateIds = $ids->slice(1)->map(fn ($id) => (int) $id)->values();

            DB::table('listings')
                ->whereIn('model_id', $duplicateIds)
                ->update(['model_id' => $keepId]);

            DB::table('car_models')->whereIn('id', $duplicateIds)->delete();
        }
    }
};

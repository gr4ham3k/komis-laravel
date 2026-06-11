<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Wykonaj tylko dla PostgreSQL i poza środowiskiem testowym
        if (DB::connection()->getDriverName() === 'pgsql' && !app()->environment('testing')) {
            DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');
        }
    }

    public function down(): void
    {
        // Wykonaj tylko dla PostgreSQL i poza środowiskiem testowym
        if (DB::connection()->getDriverName() === 'pgsql' && !app()->environment('testing')) {
            DB::statement('DROP EXTENSION IF EXISTS pg_trgm');
        }
    }
};
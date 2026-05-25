<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->decimal('price',10,2);
            $table->string('status')->default('active');
            $table->string('city');
            $table->integer('views_count')->default(0);
            $table->foreignId('brand_id')->constrained();
            $table->foreignId('model_id')->constrained('car_models');
            $table->foreignId('fuel_id')->constrained();
            $table->foreignId('transmission_id')->constrained();
            $table->foreignId('body_type_id')->constrained();
            $table->string('color');
            $table->integer('year');
            $table->integer('mileage');
            $table->integer('engine_capacity');
            $table->integer('power_hp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('listing_tags', function (Blueprint $table) {
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['listing_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_tags');
        Schema::dropIfExists('tags');
    }
};

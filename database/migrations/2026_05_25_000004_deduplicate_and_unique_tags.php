<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $duplicateNames = DB::table('tags')
            ->select('name')
            ->groupBy('name')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('name');

        foreach ($duplicateNames as $name) {
            $tagIds = DB::table('tags')
                ->where('name', $name)
                ->orderBy('id')
                ->pluck('id')
                ->values();

            if ($tagIds->count() < 2) {
                continue;
            }

            $keepId = (int) $tagIds->first();
            $duplicateIds = $tagIds->slice(1)->map(fn ($id) => (int) $id)->values();

            $listingIds = DB::table('listing_tags')
                ->whereIn('tag_id', $duplicateIds)
                ->pluck('listing_id')
                ->unique();

            foreach ($listingIds as $listingId) {
                DB::table('listing_tags')->insertOrIgnore([
                    'listing_id' => (int) $listingId,
                    'tag_id' => $keepId,
                ]);
            }

            DB::table('listing_tags')->whereIn('tag_id', $duplicateIds)->delete();
            DB::table('tags')->whereIn('id', $duplicateIds)->delete();
        }

        Schema::table('tags', function (Blueprint $table): void {
            $table->unique('name');
        });
    }

    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table): void {
            $table->dropUnique(['name']);
        });
    }
};

<?php

use App\Models\Image;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $serviceImageIds = DB::table('service_images')->pluck('image_id')->unique()->toArray();

        if (!empty($serviceImageIds)) {
            $images = Image::whereIn('id', $serviceImageIds)
                ->where('file_name', 'not like', 'services/%')
                ->get();

            foreach ($images as $image) {
                $image->update(['file_name' => 'services/' . $image->file_name]);
            }
        }
    }

    public function down(): void
    {
        $serviceImageIds = DB::table('service_images')->pluck('image_id')->unique()->toArray();

        if (!empty($serviceImageIds)) {
            $images = Image::whereIn('id', $serviceImageIds)
                ->where('file_name', 'like', 'services/%')
                ->get();

            foreach ($images as $image) {
                $image->update(['file_name' => substr($image->file_name, 9)]);
            }
        }
    }
};

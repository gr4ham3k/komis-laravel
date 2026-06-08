<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'original_name',
        'file_name',
        'file_type'
    ];

    public function listings()
    {
        return $this -> belongsToMany(Listing::class, 'listing_images') -> withPivot('sort_order');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_images')->withPivot('sort_order');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];

    public function listings()
    {
        return $this->belongsToMany(Listing::class, 'listing_tags');
    }
}

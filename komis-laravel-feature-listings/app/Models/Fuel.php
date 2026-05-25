<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fuel extends Model
{
    protected $fillable = [
        'name'
    ];

    public function listings()
    {
        return $this -> hasMany(Listing::class);
    }
}

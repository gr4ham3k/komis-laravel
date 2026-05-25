<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name'
    ];

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    public function car_models()
    {
        return $this->hasMany(CarModel::class);
    }
}

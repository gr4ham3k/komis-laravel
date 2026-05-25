<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodyType extends Model
{
    protected $fillable = [
        'name'
    ];

    public function listings()
    {
        return $this -> hasMany(Listing::class);
    }
}

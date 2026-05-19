<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = [
        'user_id',
        'brand_id',
        'model_id',
        'fuel_id',
        'transmission_id',
        'body_type_id',
        'title',
        'description',
        'price',
        'status',
        'city',
        'year',
        'mileage',
        'engine_capacity',
        'power_hp',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class,'model_id');
    }

    public function fuel()
    {
        return $this->belongsTo(Fuel::class);
    }

    public function transmission()
    {
        return $this -> belongsTo(Transmission::class);
    }

    public function bodyType()
    {
        return $this -> belongsTo(BodyType::class);
    }

    public function user()
    {
        return $this -> belongsTo(User::class);
    }

    public function images()
    {
        return $this->belongsToMany(Image::class, 'listing_images') -> withPivot('sort_order') -> orderBy('pivot_sort_order');
    }
}

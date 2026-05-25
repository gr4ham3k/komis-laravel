<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
        'color',
        'year',
        'mileage',
        'engine_capacity',
        'power_hp',
        'views_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
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
        return $this->belongsToMany(Image::class, 'listing_images')
            ->withPivot('sort_order')
            ->orderBy('listing_images.sort_order');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'listing_tags')->orderBy('name');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['brand_id'] ?? null, fn (Builder $q, $brandId) => $q->where('brand_id', $brandId))
            ->when($filters['model_id'] ?? null, fn (Builder $q, $modelId) => $q->where('model_id', $modelId))
            ->when($filters['fuel_id'] ?? null, fn (Builder $q, $fuelId) => $q->where('fuel_id', $fuelId))
            ->when($filters['transmission_id'] ?? null, fn (Builder $q, $transmissionId) => $q->where('transmission_id', $transmissionId))
            ->when($filters['city'] ?? null, fn (Builder $q, $city) => $q->where('city', 'like', '%' . trim($city) . '%'))
            ->when(
                ! empty($filters['tag_ids']) && is_array($filters['tag_ids']),
                fn (Builder $q) => $q->whereHas('tags', fn (Builder $tagQ) => $tagQ->whereIn('tags.id', $filters['tag_ids']))
            )
            ->when(
                ($filters['price_from'] ?? null) !== null && $filters['price_from'] !== '',
                fn (Builder $q) => $q->where('price', '>=', (float) $filters['price_from'])
            )
            ->when(
                ($filters['price_to'] ?? null) !== null && $filters['price_to'] !== '',
                fn (Builder $q) => $q->where('price', '<=', (float) $filters['price_to'])
            )
            ->when(
                ($filters['year_from'] ?? null) !== null && $filters['year_from'] !== '',
                fn (Builder $q) => $q->where('year', '>=', (int) $filters['year_from'])
            )
            ->when(
                ($filters['year_to'] ?? null) !== null && $filters['year_to'] !== '',
                fn (Builder $q) => $q->where('year', '<=', (int) $filters['year_to'])
            );
    }

    public function scopeSortOption(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'year_asc' => $query->orderBy('year')->orderByDesc('created_at'),
            'year_desc' => $query->orderByDesc('year')->orderByDesc('created_at'),
            'oldest' => $query->orderBy('created_at'),
            'popular' => $query->orderByDesc('views_count')->orderByDesc('created_at'),
            default => $query->orderByDesc('created_at'),
        };
    }
}

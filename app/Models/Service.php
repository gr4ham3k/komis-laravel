<?php
// app/Models/Service.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'city',
        'status',
        'views_count'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'views_count' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ServiceReview::class);
    }

    public function averageRating(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function images()
    {
        return $this->belongsToMany(Image::class, 'service_images')
            ->withPivot('sort_order')
            ->orderBy('service_images.sort_order');
    }
}
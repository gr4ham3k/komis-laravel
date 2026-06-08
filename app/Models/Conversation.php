<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{

    protected $fillable = [
        'user2_id',
        'listing_id',
        'service_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}

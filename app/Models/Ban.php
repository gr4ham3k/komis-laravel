<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    protected $fillable = [
        'user_id',
        'banned_by',
        'reason',
        'banned_at',
        'lifted_at',
    ];

    protected function casts(): array
    {
        return [
            'banned_at' => 'datetime',
            'lifted_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bannedBy()
    {
        return $this->belongsTo(User::class, 'banned_by');
    }
}

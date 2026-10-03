<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $table = 'lodging_room';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function prices(): HasMany
    {
        return $this->hasMany(RoomPrice::class, 'room_id');
    }

    public function stays(): BelongsToMany
    {
        return $this->belongsToMany(Stay::class, 'lodging_stay_rooms', 'room_id', 'stay_id');
    }

    public function checks(): HasMany
    {
        return $this->hasMany(RoomCheck::class, 'room_id');
    }

    public function chores(): HasMany
    {
        return $this->hasMany(Chore::class, 'room_id');
    }

    public function dayPrices(): HasMany
    {
        return $this->hasMany(DayPrice::class, 'room_id');
    }
}

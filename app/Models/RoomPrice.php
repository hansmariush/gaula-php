<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomPrice extends Model
{
    protected $table = 'lodging_roomprice';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'start' => 'date',
            'end' => 'date',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}

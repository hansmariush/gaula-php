<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomCheck extends Model
{
    protected $table = 'lodging_roomcheck';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'week_start' => 'date',
            'done_at' => 'datetime',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(RoomCheckItem::class, 'item_id');
    }
}

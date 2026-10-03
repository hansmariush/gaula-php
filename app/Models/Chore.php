<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chore extends Model
{
    protected $table = 'lodging_chore';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'due' => 'date',
            'done_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }
}

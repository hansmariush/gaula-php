<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeekTask extends Model
{
    protected $table = 'lodging_weektask';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'week_start' => 'date',
            'done_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function stay(): BelongsTo
    {
        return $this->belongsTo(Stay::class, 'stay_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }
}

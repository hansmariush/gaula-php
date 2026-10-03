<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeekSnapshot extends Model
{
    protected $table = 'stats_weeksnapshot';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'week_start' => 'date',
            'kg_ours' => 'float',
            'flow_avg' => 'float',
            'temp_avg' => 'float',
            'is_final' => 'boolean',
            'taken_at' => 'datetime',
        ];
    }
}

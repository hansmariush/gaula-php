<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Observation extends Model
{
    protected $table = 'stations_observation';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'time' => 'datetime',
            'value' => 'float',
            'fetched_at' => 'datetime',
        ];
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'station_id', 'nve_id');
    }

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(Parameter::class, 'parameter_id', 'code');
    }
}

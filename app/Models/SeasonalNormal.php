<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeasonalNormal extends Model
{
    protected $table = 'stations_seasonalnormal';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'median' => 'float',
            'p25' => 'float',
            'p75' => 'float',
            'computed_at' => 'datetime',
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Station extends Model
{
    protected $table = 'stations_station';

    protected $guarded = [];

    public $timestamps = false;

    protected $primaryKey = 'nve_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'parameter_codes' => 'array',
            'lat' => 'float',
            'lon' => 'float',
        ];
    }

    public function observations(): HasMany
    {
        return $this->hasMany(Observation::class, 'station_id', 'nve_id');
    }

    public function normals(): HasMany
    {
        return $this->hasMany(SeasonalNormal::class, 'station_id', 'nve_id');
    }
}

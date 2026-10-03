<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parameter extends Model
{
    protected $table = 'stations_parameter';

    protected $guarded = [];

    public $timestamps = false;

    protected $primaryKey = 'code';

    public $incrementing = false;

    public function observationSet(): HasMany
    {
        return $this->hasMany(Observation::class, 'parameter_id', 'code');
    }

    public function seasonalnormalSet(): HasMany
    {
        return $this->hasMany(SeasonalNormal::class, 'parameter_id', 'code');
    }
}

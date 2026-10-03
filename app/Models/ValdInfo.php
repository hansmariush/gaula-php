<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ValdInfo extends Model
{
    protected $table = 'vald_valdinfo';

    protected $guarded = [];

    public $timestamps = false;

    protected $primaryKey = 'elveguiden_id';

    public $incrementing = false;

    protected function casts(): array
    {
        return [
            'confirmed' => 'boolean',
            'map' => 'array',
        ];
    }

    public function facilities(): HasMany
    {
        return $this->hasMany(Facility::class, 'vald_id', 'elveguiden_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ValdPhoto::class, 'vald_id', 'elveguiden_id');
    }

    public function pools(): HasMany
    {
        return $this->hasMany(Pool::class, 'vald_id', 'elveguiden_id');
    }

    public function mapPoints(): HasMany
    {
        return $this->hasMany(MapPoint::class, 'vald_id', 'elveguiden_id');
    }
}

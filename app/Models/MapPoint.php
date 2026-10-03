<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapPoint extends Model
{
    protected $table = 'vald_mappoint';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'map' => 'array',
        ];
    }

    public function vald(): BelongsTo
    {
        return $this->belongsTo(ValdInfo::class, 'vald_id', 'elveguiden_id');
    }
}

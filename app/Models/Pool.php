<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pool extends Model
{
    protected $table = 'vald_pool';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'in_rotation' => 'boolean',
            'map' => 'array',
        ];
    }

    public function vald(): BelongsTo
    {
        return $this->belongsTo(ValdInfo::class, 'vald_id', 'elveguiden_id');
    }
}

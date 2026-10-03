<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Draw extends Model
{
    protected $table = 'lodging_draw';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'week_start' => 'date',
            'created_at' => 'datetime',
        ];
    }

    public function fishers(): HasMany
    {
        return $this->hasMany(DrawnFisher::class, 'draw_id');
    }
}

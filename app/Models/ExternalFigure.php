<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalFigure extends Model
{
    protected $table = 'stats_externalfigure';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }
}

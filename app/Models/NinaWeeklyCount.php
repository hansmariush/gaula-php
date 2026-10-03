<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NinaWeeklyCount extends Model
{
    protected $table = 'nina_ninaweeklycount';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'snapshot_date' => 'date',
        ];
    }
}

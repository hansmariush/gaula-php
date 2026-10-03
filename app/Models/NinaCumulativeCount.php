<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NinaCumulativeCount extends Model
{
    protected $table = 'nina_ninacumulativecount';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'snapshot_date' => 'date',
        ];
    }
}

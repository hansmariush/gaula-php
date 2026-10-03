<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemoClock extends Model
{
    protected $table = 'lodging_democlock';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'fake_now' => 'datetime',
        ];
    }
}

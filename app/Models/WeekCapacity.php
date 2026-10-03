<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeekCapacity extends Model
{
    protected $table = 'lodging_weekcapacity';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'week_start' => 'date',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RodPrice extends Model
{
    protected $table = 'lodging_rodprice';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'start' => 'date',
            'end' => 'date',
        ];
    }
}

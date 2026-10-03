<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeasonSync extends Model
{
    protected $table = 'catches_seasonsync';

    protected $guarded = [];

    public $timestamps = false;

    protected $primaryKey = 'year';

    public $incrementing = false;

    protected function casts(): array
    {
        return [
            'synced_at' => 'datetime',
        ];
    }
}

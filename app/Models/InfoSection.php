<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoSection extends Model
{
    protected $table = 'guide_infosection';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }
}

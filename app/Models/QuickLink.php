<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuickLink extends Model
{
    protected $table = 'guide_quicklink';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }
}

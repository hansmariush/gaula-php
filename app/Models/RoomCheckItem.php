<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomCheckItem extends Model
{
    protected $table = 'lodging_roomcheckitem';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function checks(): HasMany
    {
        return $this->hasMany(RoomCheck::class, 'item_id');
    }
}

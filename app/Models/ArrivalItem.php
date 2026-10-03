<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArrivalItem extends Model
{
    protected $table = 'guide_arrivalitem';

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
        return $this->hasMany(ArrivalCheck::class, 'item_id');
    }
}

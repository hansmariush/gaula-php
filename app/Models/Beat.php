<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Beat extends Model
{
    protected $table = 'catches_beat';

    protected $guarded = [];

    public $timestamps = false;

    protected $primaryKey = 'elveguiden_id';

    public $incrementing = false;

    public function catches(): HasMany
    {
        return $this->hasMany(SalmonCatch::class, 'beat_id', 'elveguiden_id');
    }
}

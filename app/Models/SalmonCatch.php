<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalmonCatch extends Model
{
    protected $table = 'catches_salmoncatch';

    protected $guarded = [];

    public $timestamps = false;

    protected $primaryKey = 'elveguiden_id';

    public $incrementing = false;

    protected function casts(): array
    {
        return [
            'timestamp' => 'datetime',
            'weight_kg' => 'float',
            'length_cm' => 'float',
            'released' => 'boolean',
        ];
    }

    public function beat(): BelongsTo
    {
        return $this->belongsTo(Beat::class, 'beat_id', 'elveguiden_id');
    }
}

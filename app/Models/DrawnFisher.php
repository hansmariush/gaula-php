<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DrawnFisher extends Model
{
    protected $table = 'lodging_drawnfisher';

    protected $guarded = [];

    public $timestamps = false;

    public function draw(): BelongsTo
    {
        return $this->belongsTo(Draw::class, 'draw_id');
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class, 'guest_id');
    }

    public function stay(): BelongsTo
    {
        return $this->belongsTo(Stay::class, 'stay_id');
    }
}

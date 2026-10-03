<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $table = 'crm_feedback';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'declined' => 'boolean',
            'created_at' => 'datetime',
        ];
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

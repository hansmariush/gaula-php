<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facility extends Model
{
    protected $table = 'vald_facility';

    protected $guarded = [];

    public $timestamps = false;

    public function vald(): BelongsTo
    {
        return $this->belongsTo(ValdInfo::class, 'vald_id', 'elveguiden_id');
    }
}

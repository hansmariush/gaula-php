<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValdPhoto extends Model
{
    protected $table = 'vald_valdphoto';

    protected $guarded = [];

    public $timestamps = false;

    public function vald(): BelongsTo
    {
        return $this->belongsTo(ValdInfo::class, 'vald_id', 'elveguiden_id');
    }
}

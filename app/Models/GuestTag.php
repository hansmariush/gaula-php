<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GuestTag extends Model
{
    protected $table = 'crm_guesttag';

    protected $guarded = [];

    public $timestamps = false;

    public function guests(): BelongsToMany
    {
        return $this->belongsToMany(Guest::class, 'crm_guest_tags', 'guesttag_id', 'guest_id');
    }
}

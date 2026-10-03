<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccessCode extends Model
{
    protected $table = 'access_accesscode';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'valid_from' => 'datetime',
            'valid_to' => 'datetime',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'last_used_at' => 'datetime',
        ];
    }

    public function stays(): HasMany
    {
        return $this->hasMany(Stay::class, 'access_code_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    protected $table = 'crm_guest';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'anonymized_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function stays(): HasMany
    {
        return $this->hasMany(Stay::class, 'guest_id');
    }

    public function companionStays(): BelongsToMany
    {
        return $this->belongsToMany(Stay::class, 'lodging_stay_companions', 'guest_id', 'stay_id');
    }

    public function drawn(): HasMany
    {
        return $this->hasMany(DrawnFisher::class, 'guest_id');
    }

    public function arrivalChecks(): HasMany
    {
        return $this->hasMany(ArrivalCheck::class, 'guest_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(GuestTag::class, 'crm_guest_tags', 'guest_id', 'guesttag_id');
    }

    public function catchAliases(): HasMany
    {
        return $this->hasMany(GuestCatchAlias::class, 'guest_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SentMessage::class, 'guest_id');
    }

    public function threads(): HasMany
    {
        return $this->hasMany(MessageThread::class, 'guest_id');
    }

    public function chat(): HasMany
    {
        return $this->hasMany(GuestMessage::class, 'guest_id');
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class, 'guest_id');
    }
}

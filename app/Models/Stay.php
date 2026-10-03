<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stay extends Model
{
    protected $table = 'lodging_stay';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'arrival' => 'date',
            'departure' => 'date',
            'auto_code' => 'boolean',
            'deposit_required' => 'boolean',
            'deposit_due' => 'date',
            'payment_due' => 'date',
            'created_at' => 'datetime',
            'checked_in_at' => 'datetime',
            'checked_out_at' => 'datetime',
        ];
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class, 'guest_id');
    }

    public function accessCode(): BelongsTo
    {
        return $this->belongsTo(AccessCode::class, 'access_code_id');
    }

    public function sharesWith(): BelongsTo
    {
        return $this->belongsTo(Stay::class, 'shares_with_id');
    }

    public function sharedBy(): HasMany
    {
        return $this->hasMany(Stay::class, 'shares_with_id');
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'lodging_stay_rooms', 'stay_id', 'room_id');
    }

    public function companions(): BelongsToMany
    {
        return $this->belongsToMany(Guest::class, 'lodging_stay_companions', 'stay_id', 'guest_id');
    }

    public function drawnFishers(): HasMany
    {
        return $this->hasMany(DrawnFisher::class, 'stay_id');
    }

    public function log(): HasMany
    {
        return $this->hasMany(StayNote::class, 'stay_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(WeekTask::class, 'stay_id');
    }

    public function arrivalChecks(): HasMany
    {
        return $this->hasMany(ArrivalCheck::class, 'stay_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SentMessage::class, 'stay_id');
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class, 'stay_id');
    }
}

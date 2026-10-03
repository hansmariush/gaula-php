<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestMessage extends Model
{
    protected $table = 'crm_guestmessage';

    protected $guarded = [];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'from_guest' => 'boolean',
            'created_at' => 'datetime',
            'read_at' => 'datetime',
            'deleted_at' => 'datetime',
            'hidden_for_guest' => 'boolean',
            'hidden_for_admin' => 'boolean',
        ];
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(MessageThread::class, 'thread_id');
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class, 'guest_id');
    }
}

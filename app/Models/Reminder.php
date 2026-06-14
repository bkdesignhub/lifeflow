<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = [
        'user_id', 'title', 'note', 'reminder_date', 'reminder_time', 'repeat',
        'push_enabled', 'status', 'last_notified_at',
    ];

    protected function casts(): array
    {
        return [
            'reminder_date' => 'date',
            'reminder_time' => 'datetime:H:i',
            'push_enabled' => 'boolean',
            'last_notified_at' => 'datetime',
        ];
    }
}

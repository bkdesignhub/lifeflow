<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id', 'language', 'date_format', 'time_format', 'theme',
        'task_notifications', 'reminder_notifications', 'daily_summary',
    ];

    protected function casts(): array
    {
        return [
            'task_notifications' => 'boolean',
            'reminder_notifications' => 'boolean',
            'daily_summary' => 'boolean',
        ];
    }
}

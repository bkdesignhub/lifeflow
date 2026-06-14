<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'user_id', 'title', 'start_time', 'end_time', 'repeat', 'reminder_minutes',
        'category', 'icon', 'status', 'plan_date', 'push_enabled',
    ];

    protected function casts(): array
    {
        return [
            'plan_date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'push_enabled' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

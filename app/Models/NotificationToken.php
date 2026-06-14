<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationToken extends Model
{
    protected $fillable = ['user_id', 'token', 'device_name', 'last_used_at'];

    protected function casts(): array
    {
        return ['last_used_at' => 'datetime'];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    protected $fillable = ['user_id', 'task_id', 'log_date', 'status'];

    protected function casts(): array
    {
        return ['log_date' => 'date'];
    }
}

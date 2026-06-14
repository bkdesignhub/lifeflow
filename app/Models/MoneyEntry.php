<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoneyEntry extends Model
{
    protected $fillable = ['user_id', 'type', 'amount', 'category', 'source', 'note', 'entry_date', 'icon'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'entry_date' => 'date',
        ];
    }
}

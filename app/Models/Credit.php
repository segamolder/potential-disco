<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = [
        'sum',
        'started_at',
        'percent',
        'months',
    ];

    protected $casts = [
        'started_at' => 'datetime',
    ];
}

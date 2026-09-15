<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [

        'module',

        'record_id',

        'title',

        'message',

        'type',

        'status',

        'alert_date',

        'read_at',

    ];


    protected $casts = [

        'alert_date' => 'date',

        'read_at' => 'datetime',

    ];
}
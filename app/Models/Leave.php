<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Driver;

class Leave extends Model
{
    protected $fillable = [
        'driver_id',
        'leave_type',
        'start_date',
        'end_date',
        'approval_status',
        'replacement_driver_id',
        'approval_notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function replacementDriver()
    {
        return $this->belongsTo(Driver::class, 'replacement_driver_id');
    }
}
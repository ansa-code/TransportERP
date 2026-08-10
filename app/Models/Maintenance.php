<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [

        'vehicle_id',

        'maintenance_date',

        'service_type',

        'cost',

        'workshop',

        'next_service_date',

        'notes',

    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
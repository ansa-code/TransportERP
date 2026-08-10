<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fuel extends Model
{
    protected $fillable = [

        'vehicle_id',

        'driver_id',

        'fuel_date',

        'liters',

        'price_per_liter',

        'total_amount',

        'odometer',

        'fuel_station',

        'notes',

    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
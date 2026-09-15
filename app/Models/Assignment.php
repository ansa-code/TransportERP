<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [

        // Assignment
        'assignment_no',

        // References
        'client_id',
        'vehicle_id',
        'driver_id',

        // Date Range
        'start_date',
        'end_date',

        // Rate
        'rate',
        'rate_basis',

        // Fuel
        'fuel_rule',

        // Existing fields
        'pickup_location',
        'drop_location',
        'loading_date',
        'delivery_date',
        'freight_amount',

        // Status
        'status',

        // Notes / Remarks
        'notes',
        'remarks',
    ];


    /*
    |--------------------------------------------------------------------------
    | Client
    |--------------------------------------------------------------------------
    */

    public function client()
    {
        return $this->belongsTo(Client::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Vehicle
    |--------------------------------------------------------------------------
    */

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Driver
    |--------------------------------------------------------------------------
    */

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Assignment;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Driver;

class Trip extends Model
{
    protected $fillable = [

        'trip_no',
        'assignment_id',
        'client_id',
        'vehicle_id',
        'driver_id',
        'trip_start',
        'trip_end',
        'loading_point',
        'unloading_point',
        'rate',
        'freight_amount',
        'pod_file',
        'status',
        'remarks',

    ];


    /*
    |--------------------------------------------------------------------------
    | Assignment
    |--------------------------------------------------------------------------
    */

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }


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
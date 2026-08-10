<?php

namespace App\Models;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Driver;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [

        'client_id',

        'vehicle_id',

        'driver_id',

        'pickup_location',

        'drop_location',

        'loading_date',

        'delivery_date',

        'freight_amount',

        'status',

        'notes',

    ];
    public function client()
{
    return $this->belongsTo(Client::class);
}

public function vehicle()
{
    return $this->belongsTo(Vehicle::class);
}

public function driver()
{
    return $this->belongsTo(Driver::class);
}
}
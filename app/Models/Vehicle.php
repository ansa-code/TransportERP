<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [

        'vehicle_number',

        'plate_number',

        'vehicle_type',

        'brand',

        'model',

        'manufacture_year',

        'capacity',

        'refrigerated',

        'status',

        'insurance_expiry',

        'registration_expiry',

        'notes',

    ];
}
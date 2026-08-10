<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [

        'driver_name',

        'cnic',

        'license_number',

        'license_expiry',

        'phone',

        'address',

        'date_of_birth',

        'joining_date',

        'status',

        'notes',

    ];
}
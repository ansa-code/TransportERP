<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Basic Vendor Information
        |--------------------------------------------------------------------------
        */

        'vendor_name',

        'company_name',

        'phone',

        'contact',

        'email',

        'address',

        'service_type',


        /*
        |--------------------------------------------------------------------------
        | Supplied Vehicles
        |--------------------------------------------------------------------------
        */

        'supplied_vehicle_plates',


        /*
        |--------------------------------------------------------------------------
        | Vendor Rates
        |--------------------------------------------------------------------------
        */

        'rate_per_day',

        'rate_per_month',

        'rate_per_trip',

        'custom_rate',

        'custom_rate_label',


        /*
        |--------------------------------------------------------------------------
        | Payment Terms
        |--------------------------------------------------------------------------
        */

        'payment_terms',

        'payment_terms_days',


        /*
        |--------------------------------------------------------------------------
        | Tax / Registration
        |--------------------------------------------------------------------------
        */

        'tax_registration_data',


        /*
        |--------------------------------------------------------------------------
        | Status & Notes
        |--------------------------------------------------------------------------
        */

        'status',

        'notes',

    ];


    protected $casts = [

        'rate_per_day' => 'decimal:2',

        'rate_per_month' => 'decimal:2',

        'rate_per_trip' => 'decimal:2',

        'custom_rate' => 'decimal:2',

        'payment_terms_days' => 'integer',

    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [

        'vendor_name',

        'company_name',

        'phone',

        'email',

        'address',

        'service_type',

        'status',

        'notes',

    ];
}

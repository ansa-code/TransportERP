<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [

        'client_name',

        'company_name',

        'phone',

        'email',

        'address',

        'city',

        'country',

        'status',

        'notes',

    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Assignment;

class Vehicle extends Model
{
    protected $fillable = [
        'vehicle_code',
        'vehicle_number',
        'plate_number',
        'vehicle_type',
        'brand',
        'model',
        'category',
        'load_capacity',
        'load_capacity_unit',
        'ownership_type',
        'vendor_owner',
        'registered_company_name',
        'bank_instalment_amount',
        'instalment_duration',
        'instalment_duration_unit',
        'manufacture_year',
        'capacity',
        'refrigerated',
        'status',
        'insurance_expiry',
        'registration_expiry',
        'current_odometer',
        'remarks',
        'notes',
    ];

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
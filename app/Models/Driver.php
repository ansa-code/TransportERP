<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Assignment;
use App\Models\DriverAdvance;
use App\Models\TrafficFine;

class Driver extends Model
{
    protected $fillable = [

        'driver_code',
        'driver_name',
        'cnic',
        'license_number',
        'license_expiry',
        'phone',
        'nationality',
        'passport_number',
        'passport_held_by_company',
        'visa_type',
        'visa_provided_by',
        'visa_expiry',
        'emirates_id',
        'basic_salary',
        'assigned_vehicle_id',
        'employment_status',
        'remarks',
        'address',
        'date_of_birth',
        'joining_date',
        'status',
        'notes',

    ];

    protected $casts = [
        'passport_held_by_company' => 'boolean',
        'visa_expiry' => 'date',
        'license_expiry' => 'date',
        'basic_salary' => 'decimal:2',
    ];

    /**
     * Driver assignments.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Driver salary advances.
     */
    public function advances()
    {
        return $this->hasMany(DriverAdvance::class);
    }

    /**
     * Driver traffic fines.
     */
    public function trafficFines()
    {
        return $this->hasMany(TrafficFine::class);
    }
}
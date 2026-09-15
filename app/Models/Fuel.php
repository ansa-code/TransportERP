<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fuel extends Model
{
    protected $fillable = [
        'fuel_entry_no',
        'vehicle_id',
        'driver_id',
        'client_id',
        'assignment_id',
        'trip_id',
        'fuel_date',
        'liters',
        'price_per_liter',
        'total_amount',
        'odometer',
        'fuel_station',
        'paid_by',
        'reimbursable',
        'reimbursement_amount',
        'receipt',
        'notes',
    ];

    protected $casts = [
        'fuel_date' => 'datetime',
        'liters' => 'decimal:2',
        'price_per_liter' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'odometer' => 'integer',
        'reimbursable' => 'boolean',
        'reimbursement_amount' => 'decimal:2',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function recoveries(): HasMany
    {
        return $this->hasMany(FuelRecovery::class);
    }
}
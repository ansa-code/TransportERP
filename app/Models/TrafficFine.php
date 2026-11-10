<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrafficFine extends Model
{
    protected $fillable = [
        'fine_number',
        'vehicle_id',
        'driver_id',
        'fine_date',
        'amount',
        'reason_location',
        'payment_status',
        'deduct_from_driver',
        'paid_date',
        'paid_reference',
        'attachment',
    ];

    protected $casts = [
        'fine_date' => 'date',
        'paid_date' => 'date',
        'amount' => 'decimal:2',
        'deduct_from_driver' => 'boolean',
    ];

    /**
     * Fine belongs to a vehicle.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Fine belongs to a driver.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Salary deductions allocated against this fine.
     */
    public function deductionAllocations()
    {
        return $this->hasMany(
            PayrollDeductionAllocation::class,
            'deductible_record_id'
        )->where('deduction_type', 'fine');
    }
}
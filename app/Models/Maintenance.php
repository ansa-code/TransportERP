<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'maintenance_no',
        'vehicle_id',
        'maintenance_date',
        'repair_type',
        'workshop',
        'parts_cost',
        'labour_cost',
        'other_cost',
        'total_cost',
        'odometer',
        'out_of_service_start',
        'out_of_service_end',
        'invoice_receipt',
        'status',
        'next_service_date',
        'remarks',
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'next_service_date' => 'date',
        'out_of_service_start' => 'date',
        'out_of_service_end' => 'date',

        'parts_cost' => 'decimal:2',
        'labour_cost' => 'decimal:2',
        'other_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',

        'odometer' => 'integer',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Driver;
use App\Models\User;

class DriverAdvance extends Model
{
    protected $fillable = [
        'advance_no',
        'driver_id',
        'advance_date',
        'amount',
        'reason',
        'approved_by',
        'deducted_amount',
        'remaining_amount',
        'status',
        'remarks',
    ];

    protected $casts = [
        'advance_date' => 'date',
        'amount' => 'decimal:2',
        'deducted_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
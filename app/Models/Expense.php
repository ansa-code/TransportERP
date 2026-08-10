<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [

        'vehicle_id',

        'expense_date',

        'expense_type',

        'amount',

        'vendor',

        'notes',

    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
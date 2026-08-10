<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Driver;

class Payroll extends Model
{
    protected $fillable = [

        'driver_id',

        'salary_month',

        'basic_salary',

        'allowance',

        'deduction',

        'net_salary',

        'payment_date',

        'status',

        'notes',

    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
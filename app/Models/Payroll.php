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

        'overtime',

        'visa_deduction',

        'fine_deduction',

        'advance_deduction',

        'other_deduction',

        'other_deduction_reason',

        'total_deductions',

        'net_salary',

        'payment_date',

        'paid_date',

        'paid_reference',

        'payment_status',

        'deduction',

        'status',

        'notes',

    ];

    protected $casts = [

        'basic_salary' => 'decimal:2',

        'allowance' => 'decimal:2',

        'overtime' => 'decimal:2',

        'visa_deduction' => 'decimal:2',

        'fine_deduction' => 'decimal:2',

        'advance_deduction' => 'decimal:2',

        'other_deduction' => 'decimal:2',

        'total_deductions' => 'decimal:2',

        'net_salary' => 'decimal:2',

        'payment_date' => 'date',

        'paid_date' => 'date',

    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
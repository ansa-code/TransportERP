<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_no',
        'category',
        'expense_type',
        'expense_date',
        'vehicle_id',
        'driver_id',
        'client_id',
        'assignment_id',
        'trip_id',
        'amount',
        'parts_cost',
        'labour_cost',
        'other_cost',
        'payment_status',
        'payment_method_reference',
        'reimbursable',
        'receipt',
        'remarks',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'expense_type' => 'array',
        'amount' => 'decimal:2',
        'parts_cost' => 'decimal:2',
        'labour_cost' => 'decimal:2',
        'other_cost' => 'decimal:2',
        'reimbursable' => 'boolean',
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
}
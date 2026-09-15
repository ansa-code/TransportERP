<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\PaymentAllocation;

class Payment extends Model
{
    protected $fillable = [
        'payment_no',
        'client_id',
        'payment_date',
        'amount',
        'payment_method',
        'reference',
        'unallocated_amount',
        'notes',
        'attachment',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'unallocated_amount' => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | Client
    |--------------------------------------------------------------------------
    */

    public function client()
    {
        return $this->belongsTo(Client::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Payment Allocations
    |--------------------------------------------------------------------------
    */

    public function allocations()
    {
        return $this->hasMany(
            PaymentAllocation::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Invoices
    |--------------------------------------------------------------------------
    */

    public function invoices()
    {
        return $this->belongsToMany(
            Invoice::class,
            'payment_allocations'
        )
        ->withPivot('allocated_amount')
        ->withTimestamps();
    }
}
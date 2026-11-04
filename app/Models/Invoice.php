<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Assignment;
use App\Models\Trip;
use App\Models\PaymentAllocation;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'invoice_no',
        'client_id',
        'assignment_id',
        'billing_start',
        'billing_end',
        'invoice_date',
        'due_date',
        'source',
        'amount',
        'subtotal',
        'vat_percent',
        'vat_amount',
        'fuel_reimbursement',
        'other_reimbursement',
        'total_amount',
        'paid_amount',
        'balance',
        'status',
        'notes',
        'pdf',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'billing_start' => 'date',
        'billing_end' => 'date',
        'vat_percent' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'fuel_reimbursement' => 'decimal:2',
        'other_reimbursement' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Existing single Assignment relationship
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    // Multiple Assignments linked to this invoice
    public function assignments(): BelongsToMany
    {
        return $this->belongsToMany(
            Assignment::class,
            'invoice_assignments'
        )->withTimestamps();
    }

    // Multiple Trips linked to this invoice
    public function trips(): BelongsToMany
    {
        return $this->belongsToMany(
            Trip::class,
            'invoice_trips'
        )->withTimestamps();
    }

    public function fuelRecoveries(): HasMany
    {
        return $this->hasMany(FuelRecovery::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PaymentAllocation::class);
    }
}
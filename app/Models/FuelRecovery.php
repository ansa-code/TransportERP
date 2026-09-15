<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelRecovery extends Model
{
    protected $fillable = [
        'fuel_id',
        'invoice_id',
        'recovery_amount',
    ];

    protected $casts = [
        'recovery_amount' => 'decimal:2',
    ];

    public function fuel(): BelongsTo
    {
        return $this->belongsTo(Fuel::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
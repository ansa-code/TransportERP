<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [

        // Basic Information
        'client_code',
        'client_name',
        'company_name',
        'contact_person',
        'phone',
        'email',
        'address',
        'city',
        'country',

        // Trade Licence / Tax
        'trade_licence',
        'trade_licence_expiry',
        'trn',

        // Contract
        'contract_start',
        'contract_end',

        // Billing
        'billing_type',
        'vat_applicable',
        'payment_terms',
        'credit_days',
        'credit_limit',

        // Fuel Reimbursement
        'fuel_reimbursement_rule',

        // Status / Notes
        'status',
        'notes',
    ];

    protected $casts = [
        'trade_licence_expiry' => 'date',
        'contract_start' => 'date',
        'contract_end' => 'date',
        'vat_applicable' => 'boolean',
        'credit_days' => 'integer',
        'credit_limit' => 'decimal:2',
    ];
}
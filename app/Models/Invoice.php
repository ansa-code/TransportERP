<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Assignment;

class Invoice extends Model
{
    protected $fillable = [

        'invoice_number',

        'client_id',

        'assignment_id',

        'invoice_date',

        'due_date',

        'amount',

        'status',

        'notes',

    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}
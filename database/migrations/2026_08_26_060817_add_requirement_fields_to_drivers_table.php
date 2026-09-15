<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fields were already created during the previous migration attempts.
    }

    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {

            $table->dropColumn([
                'driver_code',
                'nationality',
                'passport_number',
                'passport_held_by_company',
                'visa_type',
                'visa_provided_by',
                'visa_expiry',
                'emirates_id',
                'basic_salary',
                'assigned_vehicle_id',
                'employment_status',
                'remarks',
            ]);

        });
    }
};
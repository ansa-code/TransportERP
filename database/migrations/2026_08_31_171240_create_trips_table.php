<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Trip Number
            |--------------------------------------------------------------------------
            */

            $table->string('trip_no')->unique();


            /*
            |--------------------------------------------------------------------------
            | References
            |--------------------------------------------------------------------------
            */

            $table->foreignId('assignment_id')
                ->nullable()
                ->constrained('assignments')
                ->nullOnDelete();

            $table->foreignId('client_id')
                ->constrained('clients')
                ->cascadeOnDelete();

            $table->foreignId('vehicle_id')
                ->constrained('vehicles')
                ->cascadeOnDelete();

            $table->foreignId('driver_id')
                ->constrained('drivers')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Trip Date / Time
            |--------------------------------------------------------------------------
            */

            $table->dateTime('trip_start');
            $table->dateTime('trip_end')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Locations
            |--------------------------------------------------------------------------
            */

            $table->string('loading_point');

            $table->string('unloading_point');


            /*
            |--------------------------------------------------------------------------
            | Rate / Freight
            |--------------------------------------------------------------------------
            */

            $table->decimal('rate', 12, 2)
                ->default(0);

            $table->decimal('freight_amount', 12, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | POD / Attachments
            |--------------------------------------------------------------------------
            */

            $table->string('pod_file')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->string('status')
                ->default('Planned');


            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            $table->text('remarks')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
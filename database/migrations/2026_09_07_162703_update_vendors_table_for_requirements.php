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
        Schema::table('vendors', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | FRD: Contact
            |--------------------------------------------------------------------------
            */

            $table->string('contact')
                ->nullable()
                ->after('phone');


            /*
            |--------------------------------------------------------------------------
            | FRD: Vehicle(s) Supplied
            | Store supplied vehicle plate numbers.
            |--------------------------------------------------------------------------
            */

            $table->text('supplied_vehicle_plates')
                ->nullable()
                ->after('service_type');


            /*
            |--------------------------------------------------------------------------
            | FRD: Rates
            | Supports day / month / trip / custom.
            |--------------------------------------------------------------------------
            */

            $table->decimal('rate_per_day', 12, 2)
                ->nullable()
                ->after('supplied_vehicle_plates');

            $table->decimal('rate_per_month', 12, 2)
                ->nullable()
                ->after('rate_per_day');

            $table->decimal('rate_per_trip', 12, 2)
                ->nullable()
                ->after('rate_per_month');

            $table->decimal('custom_rate', 12, 2)
                ->nullable()
                ->after('rate_per_trip');

            $table->string('custom_rate_label')
                ->nullable()
                ->after('custom_rate');


            /*
            |--------------------------------------------------------------------------
            | FRD: Payment Terms
            |--------------------------------------------------------------------------
            */

            $table->string('payment_terms')
                ->nullable()
                ->after('custom_rate_label');

            $table->unsignedInteger('payment_terms_days')
                ->nullable()
                ->after('payment_terms');


            /*
            |--------------------------------------------------------------------------
            | FRD: Tax / Registration Data
            |--------------------------------------------------------------------------
            */

            $table->text('tax_registration_data')
                ->nullable()
                ->after('payment_terms_days');


            /*
            |--------------------------------------------------------------------------
            | FRD: Status
            |--------------------------------------------------------------------------
            */

            $table->string('status')
                ->default('Active')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {

            $table->dropColumn([
                'contact',
                'supplied_vehicle_plates',
                'rate_per_day',
                'rate_per_month',
                'rate_per_trip',
                'custom_rate',
                'custom_rate_label',
                'payment_terms',
                'payment_terms_days',
                'tax_registration_data',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Restore original status definition
            |--------------------------------------------------------------------------
            */

            $table->string('status')
                ->change();
        });
    }
};
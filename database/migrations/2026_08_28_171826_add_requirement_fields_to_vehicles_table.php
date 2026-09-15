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
        Schema::table('vehicles', function (Blueprint $table) {

            $table->string('vehicle_code')->nullable()->after('id');

            $table->string('category')->nullable()->after('model');

            $table->decimal('load_capacity', 12, 2)
                ->nullable()
                ->after('category');

            $table->string('load_capacity_unit')
                ->nullable()
                ->after('load_capacity');

            $table->string('ownership_type')
                ->default('Company Owned')
                ->after('load_capacity_unit');

            $table->string('vendor_owner')
                ->nullable()
                ->after('ownership_type');

            $table->string('registered_company_name')
                ->nullable()
                ->after('vendor_owner');

            $table->decimal('bank_instalment_amount', 12, 2)
                ->nullable()
                ->after('registered_company_name');

            $table->integer('instalment_duration')
                ->nullable()
                ->after('bank_instalment_amount');

            $table->string('instalment_duration_unit')
                ->default('Months')
                ->after('instalment_duration');

            $table->unsignedBigInteger('current_odometer')
                ->nullable()
                ->after('registration_expiry');

            $table->string('remarks')
                ->nullable()
                ->after('current_odometer');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {

            $table->dropUnique(['vehicle_code']);

            $table->dropColumn([
                'vehicle_code',
                'category',
                'load_capacity',
                'load_capacity_unit',
                'ownership_type',
                'vendor_owner',
                'registered_company_name',
                'bank_instalment_amount',
                'instalment_duration',
                'instalment_duration_unit',
                'current_odometer',
                'remarks',
            ]);

        });
    }
};
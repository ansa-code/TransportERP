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
        Schema::table('assignments', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Assignment Number
            |--------------------------------------------------------------------------
            */

            $table->string('assignment_no')
                ->nullable()
                ->unique()
                ->after('id');


            /*
            |--------------------------------------------------------------------------
            | Assignment Date Range
            |--------------------------------------------------------------------------
            */

            $table->date('start_date')
                ->nullable()
                ->after('driver_id');

            $table->date('end_date')
                ->nullable()
                ->after('start_date');


            /*
            |--------------------------------------------------------------------------
            | Rate
            |--------------------------------------------------------------------------
            */

            $table->decimal('rate', 12, 2)
                ->nullable()
                ->after('end_date');


            /*
            |--------------------------------------------------------------------------
            | Rate Basis
            |--------------------------------------------------------------------------
            */

            $table->string('rate_basis')
                ->default('Monthly')
                ->after('rate');


            /*
            |--------------------------------------------------------------------------
            | Fuel Rule
            |--------------------------------------------------------------------------
            */

            $table->string('fuel_rule')
                ->nullable()
                ->after('rate_basis');


            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            $table->text('remarks')
                ->nullable()
                ->after('status');

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {

            $table->dropUnique([
                'assignments_assignment_no_unique'
            ]);

            $table->dropColumn([
                'assignment_no',
                'start_date',
                'end_date',
                'rate',
                'rate_basis',
                'fuel_rule',
                'remarks',
            ]);

        });
    }
};
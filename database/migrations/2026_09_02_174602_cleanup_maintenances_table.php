<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Rename existing fields
        |--------------------------------------------------------------------------
        */

        Schema::table('maintenances', function (Blueprint $table) {
            $table->renameColumn('service_type', 'repair_type');
            $table->renameColumn('cost', 'total_cost');
            $table->renameColumn('notes', 'remarks');
        });


        /*
        |--------------------------------------------------------------------------
        | Add Maintenance No.
        |--------------------------------------------------------------------------
        */

        Schema::table('maintenances', function (Blueprint $table) {

            $table->string('maintenance_no')
                ->nullable()
                ->after('id');

            $table->decimal('parts_cost', 10, 2)
                ->default(0)
                ->after('repair_type');

            $table->decimal('labour_cost', 10, 2)
                ->default(0)
                ->after('parts_cost');

            $table->decimal('other_cost', 10, 2)
                ->default(0)
                ->after('labour_cost');

            $table->unsignedInteger('odometer')
                ->nullable()
                ->after('total_cost');

            $table->date('out_of_service_start')
                ->nullable()
                ->after('odometer');

            $table->date('out_of_service_end')
                ->nullable()
                ->after('out_of_service_start');

            $table->string('invoice_receipt')
                ->nullable()
                ->after('out_of_service_end');

            $table->string('status')
                ->default('Open')
                ->after('invoice_receipt');
        });


        /*
        |--------------------------------------------------------------------------
        | Preserve existing total cost
        |--------------------------------------------------------------------------
        |
        | Old "cost" was a combined amount.
        | We preserve it in "total_cost".
        |
        */

        DB::table('maintenances')
            ->orderBy('id')
            ->each(function ($maintenance) {

                DB::table('maintenances')
                    ->where('id', $maintenance->id)
                    ->update([
                        'maintenance_no' =>
                            'AST-MNT-' .
                            date('Y') .
                            '-' .
                            str_pad(
                                $maintenance->id,
                                5,
                                '0',
                                STR_PAD_LEFT
                            ),

                        'parts_cost' => 0,

                        'labour_cost' => 0,

                        'other_cost' => $maintenance->total_cost ?? 0,

                        'total_cost' => $maintenance->total_cost ?? 0,
                    ]);
            });


        /*
        |--------------------------------------------------------------------------
        | Maintenance No. unique
        |--------------------------------------------------------------------------
        */

        Schema::table('maintenances', function (Blueprint $table) {
            $table->unique('maintenance_no');
        });


        /*
        |--------------------------------------------------------------------------
        | Final column adjustments
        |--------------------------------------------------------------------------
        */

        Schema::table('maintenances', function (Blueprint $table) {

            $table->string('repair_type')
                ->nullable(false)
                ->change();

            $table->string('workshop')
                ->nullable()
                ->change();

            $table->decimal('total_cost', 10, 2)
                ->default(0)
                ->change();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {

            $table->dropUnique([
                'maintenances_maintenance_no_unique'
            ]);

            $table->dropColumn([
                'maintenance_no',
                'parts_cost',
                'labour_cost',
                'other_cost',
                'odometer',
                'out_of_service_start',
                'out_of_service_end',
                'invoice_receipt',
                'status',
            ]);
        });

        Schema::table('maintenances', function (Blueprint $table) {

            $table->renameColumn('repair_type', 'service_type');
            $table->renameColumn('total_cost', 'cost');
            $table->renameColumn('remarks', 'notes');
        });
    }
};
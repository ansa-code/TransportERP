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
        | Add new Maintenance fields
        |--------------------------------------------------------------------------
        */

        Schema::table('maintenances', function (Blueprint $table) {

            // Maintenance number
            $table->string('maintenance_no')
                ->nullable()
                ->unique()
                ->after('id');

            // Requirement: Repair Type
            $table->string('repair_type')
                ->nullable()
                ->after('maintenance_date');

            // Separate maintenance costs
            $table->decimal('parts_cost', 10, 2)
                ->default(0)
                ->after('repair_type');

            $table->decimal('labour_cost', 10, 2)
                ->default(0)
                ->after('parts_cost');

            $table->decimal('other_cost', 10, 2)
                ->default(0)
                ->after('labour_cost');

            // Calculated total
            $table->decimal('total_cost', 10, 2)
                ->default(0)
                ->after('other_cost');

            // Current vehicle odometer
            $table->unsignedInteger('odometer')
                ->nullable()
                ->after('total_cost');

            // Out-of-service period
            $table->date('out_of_service_start')
                ->nullable()
                ->after('odometer');

            $table->date('out_of_service_end')
                ->nullable()
                ->after('out_of_service_start');

            // Supporting document / reference
            $table->string('invoice_receipt')
                ->nullable()
                ->after('out_of_service_end');

            // Maintenance status
            $table->string('status')
                ->default('Open')
                ->after('invoice_receipt');

            // Remarks
            $table->text('remarks')
                ->nullable()
                ->after('status');
        });


        /*
        |--------------------------------------------------------------------------
        | Preserve old data
        |--------------------------------------------------------------------------
        |
        | Old "service_type" becomes "repair_type".
        | Old "cost" is preserved as "total_cost".
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
                            str_pad($maintenance->id, 5, '0', STR_PAD_LEFT),

                        'repair_type' =>
                            $maintenance->service_type,

                        'parts_cost' => 0,

                        'labour_cost' =>
                            $maintenance->cost ?? 0,

                        'other_cost' => 0,

                        'total_cost' =>
                            $maintenance->cost ?? 0,

                    ]);
            });


        /*
        |--------------------------------------------------------------------------
        | Make Repair Type required
        |--------------------------------------------------------------------------
        */

        Schema::table('maintenances', function (Blueprint $table) {

            $table->string('repair_type')
                ->nullable(false)
                ->change();

            $table->string('workshop')
                ->nullable()
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
                'maintenance_no'
            ]);

            $table->dropColumn([
                'maintenance_no',
                'repair_type',
                'parts_cost',
                'labour_cost',
                'other_cost',
                'total_cost',
                'odometer',
                'out_of_service_start',
                'out_of_service_end',
                'invoice_receipt',
                'status',
                'remarks',
            ]);

        });
    }
};

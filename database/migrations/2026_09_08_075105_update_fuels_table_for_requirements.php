<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Fuel Entry Number
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasColumn('fuels', 'fuel_entry_no')) {
            Schema::table('fuels', function (Blueprint $table) {
                $table->string('fuel_entry_no')
                    ->nullable()
                    ->after('id');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Client / Assignment / Trip
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasColumn('fuels', 'client_id')) {
            Schema::table('fuels', function (Blueprint $table) {
                $table->foreignId('client_id')
                    ->nullable()
                    ->after('driver_id')
                    ->constrained()
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('fuels', 'assignment_id')) {
            Schema::table('fuels', function (Blueprint $table) {
                $table->foreignId('assignment_id')
                    ->nullable()
                    ->after('client_id')
                    ->constrained()
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('fuels', 'trip_id')) {
            Schema::table('fuels', function (Blueprint $table) {
                $table->foreignId('trip_id')
                    ->nullable()
                    ->after('assignment_id')
                    ->constrained()
                    ->nullOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Payment / Reimbursement
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasColumn('fuels', 'paid_by')) {
            Schema::table('fuels', function (Blueprint $table) {
                $table->string('paid_by')
                    ->default('AL SHAQRA')
                    ->after('total_amount');
            });
        }

        if (!Schema::hasColumn('fuels', 'reimbursable')) {
            Schema::table('fuels', function (Blueprint $table) {
                $table->boolean('reimbursable')
                    ->default(false)
                    ->after('paid_by');
            });
        }

        if (!Schema::hasColumn('fuels', 'reimbursement_amount')) {
            Schema::table('fuels', function (Blueprint $table) {
                $table->decimal('reimbursement_amount', 10, 2)
                    ->nullable()
                    ->after('reimbursable');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Receipt
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasColumn('fuels', 'receipt')) {
            Schema::table('fuels', function (Blueprint $table) {
                $table->string('receipt')
                    ->nullable()
                    ->after('reimbursement_amount');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Driver / Date / Odometer
        |--------------------------------------------------------------------------
        */

        DB::statement(
            'ALTER TABLE fuels MODIFY driver_id BIGINT UNSIGNED NULL'
        );

        DB::statement(
            'ALTER TABLE fuels MODIFY fuel_date DATETIME NOT NULL'
        );

        DB::statement(
            'ALTER TABLE fuels MODIFY odometer INT NULL'
        );

        /*
        |--------------------------------------------------------------------------
        | Generate Fuel Entry Numbers
        |--------------------------------------------------------------------------
        */

        $fuels = DB::table('fuels')
            ->where(function ($query) {
                $query->whereNull('fuel_entry_no')
                    ->orWhere('fuel_entry_no', '');
            })
            ->orderBy('id')
            ->get();

        foreach ($fuels as $fuel) {

            $year = date('Y', strtotime($fuel->fuel_date));

            $fuelEntryNo = 'AST-FUL-' . $year . '-' . str_pad(
                $fuel->id,
                5,
                '0',
                STR_PAD_LEFT
            );

            DB::table('fuels')
                ->where('id', $fuel->id)
                ->update([
                    'fuel_entry_no' => $fuelEntryNo,
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Unique Fuel Entry Number
        |--------------------------------------------------------------------------
        */

        $indexes = DB::select(
            "SHOW INDEX FROM fuels
             WHERE Key_name = 'fuels_fuel_entry_no_unique'"
        );

        if (empty($indexes)) {
            Schema::table('fuels', function (Blueprint $table) {
                $table->unique('fuel_entry_no');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('fuels', 'fuel_entry_no')) {

            $indexes = DB::select(
                "SHOW INDEX FROM fuels
                 WHERE Key_name = 'fuels_fuel_entry_no_unique'"
            );

            if (!empty($indexes)) {
                Schema::table('fuels', function (Blueprint $table) {
                    $table->dropUnique('fuels_fuel_entry_no_unique');
                });
            }
        }

        if (Schema::hasColumn('fuels', 'client_id')) {
            $this->dropForeignIfExists('fuels', 'client_id');
        }

        if (Schema::hasColumn('fuels', 'assignment_id')) {
            $this->dropForeignIfExists('fuels', 'assignment_id');
        }

        if (Schema::hasColumn('fuels', 'trip_id')) {
            $this->dropForeignIfExists('fuels', 'trip_id');
        }

        $columns = [
            'fuel_entry_no',
            'client_id',
            'assignment_id',
            'trip_id',
            'paid_by',
            'reimbursable',
            'reimbursement_amount',
            'receipt',
        ];

        $existingColumns = array_filter(
            $columns,
            fn ($column) => Schema::hasColumn('fuels', $column)
        );

        if (!empty($existingColumns)) {
            Schema::table('fuels', function (Blueprint $table) use ($existingColumns) {
                $table->dropColumn($existingColumns);
            });
        }

        DB::statement(
            'ALTER TABLE fuels MODIFY driver_id BIGINT UNSIGNED NOT NULL'
        );

        DB::statement(
            'ALTER TABLE fuels MODIFY fuel_date DATE NOT NULL'
        );

        DB::statement(
            'ALTER TABLE fuels MODIFY odometer INT NOT NULL'
        );
    }

    /**
     * Drop a foreign key only when it exists.
     */
    private function dropForeignIfExists(
        string $table,
        string $column
    ): void {
        $foreignKeys = DB::select(
            "SELECT CONSTRAINT_NAME
             FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE()
             AND TABLE_NAME = ?
             AND COLUMN_NAME = ?
             AND REFERENCED_TABLE_NAME IS NOT NULL",
            [$table, $column]
        );

        foreach ($foreignKeys as $foreignKey) {
            DB::statement(
                "ALTER TABLE `{$table}`
                 DROP FOREIGN KEY `{$foreignKey->CONSTRAINT_NAME}`"
            );
        }
    }
};
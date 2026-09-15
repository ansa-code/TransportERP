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
        Schema::table('expenses', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Vehicle
            |--------------------------------------------------------------------------
            */

            $table->dropForeign(['vehicle_id']);

            $table->foreignId('vehicle_id')
                ->nullable()
                ->change();


            /*
            |--------------------------------------------------------------------------
            | Driver
            |--------------------------------------------------------------------------
            */

            $table->foreignId('driver_id')
                ->nullable()
                ->after('vehicle_id')
                ->constrained('drivers')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Client
            |--------------------------------------------------------------------------
            */

            $table->foreignId('client_id')
                ->nullable()
                ->after('driver_id')
                ->constrained('clients')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Assignment
            |--------------------------------------------------------------------------
            */

            $table->foreignId('assignment_id')
                ->nullable()
                ->after('client_id')
                ->constrained('assignments')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Trip
            |--------------------------------------------------------------------------
            */

            $table->foreignId('trip_id')
                ->nullable()
                ->after('assignment_id')
                ->constrained('trips')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Expense Number
            |--------------------------------------------------------------------------
            */

            $table->string('expense_no')
                ->unique()
                ->after('id');


            /*
            |--------------------------------------------------------------------------
            | Category
            |--------------------------------------------------------------------------
            */

            $table->enum('category', [
                'Fuel',
                'Maintenance',
                'Visa',
                'Fine',
                'Registration',
                'Insurance',
                'Admin',
                'Other',
            ])
            ->after('expense_type');


            /*
            |--------------------------------------------------------------------------
            | Payment Status
            |--------------------------------------------------------------------------
            */

            $table->enum('payment_status', [
                'Pending',
                'Paid',
                'Cancelled',
            ])
            ->default('Pending')
            ->after('amount');


            /*
            |--------------------------------------------------------------------------
            | Payment Method / Reference
            |--------------------------------------------------------------------------
            */

            $table->string('payment_method_reference')
                ->nullable()
                ->after('payment_status');


            /*
            |--------------------------------------------------------------------------
            | Reimbursable
            |--------------------------------------------------------------------------
            */

            $table->boolean('reimbursable')
                ->default(false)
                ->after('payment_method_reference');
            /*
            |--------------------------------------------------------------------------
            | Receipt
            |--------------------------------------------------------------------------
            */

            $table->string('receipt')
                ->nullable()
                ->after('reimbursable');


            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            $table->text('remarks')
                ->nullable()
                ->after('receipt');


            /*
            |--------------------------------------------------------------------------
            | Remove Old Expense Type
            |--------------------------------------------------------------------------
            */

            $table->dropColumn('expense_type');


            /*
            |--------------------------------------------------------------------------
            | Remove Old Vendor Field
            |--------------------------------------------------------------------------
            */

            $table->dropColumn('vendor');


            /*
            |--------------------------------------------------------------------------
            | Rename Notes to Remarks
            |--------------------------------------------------------------------------
            */

            $table->dropColumn('notes');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Restore Old Fields
            |--------------------------------------------------------------------------
            */

            $table->string('expense_type')
                ->nullable()
                ->after('expense_date');

            $table->string('vendor')
                ->nullable()
                ->after('amount');

            $table->text('notes')
                ->nullable()
                ->after('vendor');


            /*
            |--------------------------------------------------------------------------
            | Remove New Foreign Keys
            |--------------------------------------------------------------------------
            */

            $table->dropForeign(['driver_id']);
            $table->dropForeign(['client_id']);
            $table->dropForeign(['assignment_id']);
            $table->dropForeign(['trip_id']);


            /*
            |--------------------------------------------------------------------------
            | Remove New Columns
            |--------------------------------------------------------------------------
            */

            $table->dropColumn([
                'driver_id',
                'client_id',
                'assignment_id',
                'trip_id',
                'expense_no',
                'category',
                'payment_status',
                'payment_method_reference',
                'reimbursable',
                'receipt',
                'remarks',
            ]);

        });
    }
};
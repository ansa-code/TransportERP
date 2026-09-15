<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Add New Invoice Fields
        |--------------------------------------------------------------------------
        */

        Schema::table('invoices', function (Blueprint $table) {

            $table->string('invoice_no')
                ->nullable()
                ->after('invoice_number');

            $table->date('billing_start')
                ->nullable()
                ->after('client_id');

            $table->date('billing_end')
                ->nullable()
                ->after('billing_start');

            $table->string('source')
                ->nullable()
                ->after('assignment_id');

            $table->decimal('subtotal', 12, 2)
                ->default(0)
                ->after('due_date');

            $table->decimal('vat_percent', 5, 2)
                ->default(0)
                ->after('subtotal');

            $table->decimal('vat_amount', 12, 2)
                ->default(0)
                ->after('vat_percent');

            $table->decimal('fuel_reimbursement', 12, 2)
                ->default(0)
                ->after('vat_amount');

            $table->decimal('other_reimbursement', 12, 2)
                ->default(0)
                ->after('fuel_reimbursement');

            $table->decimal('total_amount', 12, 2)
                ->default(0)
                ->after('other_reimbursement');

            $table->decimal('paid_amount', 12, 2)
                ->default(0)
                ->after('total_amount');

            $table->decimal('balance', 12, 2)
                ->default(0)
                ->after('paid_amount');

            $table->string('pdf')
                ->nullable()
                ->after('notes');
        });


        /*
        |--------------------------------------------------------------------------
        | Preserve Existing Invoice Data
        |--------------------------------------------------------------------------
        */

        DB::statement("
            UPDATE invoices
            SET
                invoice_no = invoice_number,
                subtotal = COALESCE(amount, 0),
                total_amount = COALESCE(amount, 0),
                paid_amount = 0,
                balance = COALESCE(amount, 0)
        ");


        /*
        |--------------------------------------------------------------------------
        | Existing Assignment Becomes Billing Source
        |--------------------------------------------------------------------------
        */

        DB::statement("
            UPDATE invoices
            SET source = 'Assignment'
            WHERE assignment_id IS NOT NULL
              AND (source IS NULL OR source = '')
        ");


        /*
        |--------------------------------------------------------------------------
        | Make Invoice Number Required + Unique
        |--------------------------------------------------------------------------
        */

        Schema::table('invoices', function (Blueprint $table) {

            $table->unique('invoice_no');
        });
    }


    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {

            $table->dropUnique([
                'invoice_no'
            ]);

            $table->dropColumn([
                'invoice_no',
                'billing_start',
                'billing_end',
                'source',
                'subtotal',
                'vat_percent',
                'vat_amount',
                'fuel_reimbursement',
                'other_reimbursement',
                'total_amount',
                'paid_amount',
                'balance',
                'pdf',
            ]);
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = Schema::getColumnListing('clients');

        Schema::table('clients', function (Blueprint $table) use ($columns) {

            if (!in_array('trade_licence_expiry', $columns)) {
                $table->date('trade_licence_expiry')
                    ->nullable()
                    ->after('trade_licence');
            }

            if (!in_array('trn', $columns)) {
                $table->string('trn')
                    ->nullable()
                    ->after('trade_licence_expiry');
            }

            if (!in_array('contract_start', $columns)) {
                $table->date('contract_start')
                    ->nullable()
                    ->after('trn');
            }

            if (!in_array('contract_end', $columns)) {
                $table->date('contract_end')
                    ->nullable()
                    ->after('contract_start');
            }

            if (!in_array('billing_type', $columns)) {
                $table->string('billing_type')
                    ->default('Per Trip')
                    ->after('contract_end');
            }

            if (!in_array('vat_applicable', $columns)) {
                $table->boolean('vat_applicable')
                    ->default(true)
                    ->after('billing_type');
            }

            if (!in_array('payment_terms', $columns)) {
                $table->string('payment_terms')
                    ->nullable()
                    ->after('vat_applicable');
            }

            if (!in_array('credit_days', $columns)) {
                $table->unsignedInteger('credit_days')
                    ->nullable()
                    ->after('payment_terms');
            }

            if (!in_array('credit_limit', $columns)) {
                $table->decimal('credit_limit', 12, 2)
                    ->nullable()
                    ->after('credit_days');
            }

            if (!in_array('fuel_reimbursement_rule', $columns)) {
                $table->string('fuel_reimbursement_rule')
                    ->default('None')
                    ->after('credit_limit');
            }
        });
    }

    public function down(): void
    {
        $columns = Schema::getColumnListing('clients');

        $remove = [
            'trade_licence_expiry',
            'trn',
            'contract_start',
            'contract_end',
            'billing_type',
            'vat_applicable',
            'payment_terms',
            'credit_days',
            'credit_limit',
            'fuel_reimbursement_rule',
        ];

        $remove = array_values(array_filter(
            $remove,
            fn ($column) => in_array($column, $columns)
        ));

        if (!empty($remove)) {
            Schema::table('clients', function (Blueprint $table) use ($remove) {
                $table->dropColumn($remove);
            });
        }
    }
};
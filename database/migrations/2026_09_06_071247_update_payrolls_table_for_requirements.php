<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {

            // Payroll month remains in salary_month.
            // Add unique driver + month rule after existing data is checked.

            $table->decimal('overtime', 10, 2)
                ->default(0)
                ->after('allowance');

            $table->decimal('visa_deduction', 10, 2)
                ->default(0)
                ->after('overtime');

            $table->decimal('fine_deduction', 10, 2)
                ->default(0)
                ->after('visa_deduction');

            $table->decimal('advance_deduction', 10, 2)
                ->default(0)
                ->after('fine_deduction');

            $table->decimal('other_deduction', 10, 2)
                ->default(0)
                ->after('advance_deduction');

            $table->text('other_deduction_reason')
                ->nullable()
                ->after('other_deduction');

            $table->decimal('total_deductions', 10, 2)
                ->default(0)
                ->after('other_deduction_reason');

            $table->date('paid_date')
                ->nullable()
                ->after('payment_date');

            $table->string('paid_reference')
                ->nullable()
                ->after('paid_date');

            $table->enum('payment_status', [
                'Pending',
                'Paid',
                'Cancelled'
            ])
                ->default('Pending')
                ->after('paid_reference');
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {

            $table->dropColumn([
                'overtime',
                'visa_deduction',
                'fine_deduction',
                'advance_deduction',
                'other_deduction',
                'other_deduction_reason',
                'total_deductions',
                'paid_date',
                'paid_reference',
                'payment_status',
            ]);
        });
    }
};
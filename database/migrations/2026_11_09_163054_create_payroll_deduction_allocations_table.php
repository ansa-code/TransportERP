<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_deduction_allocations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payroll_id')
                ->constrained('payrolls')
                ->cascadeOnDelete();

            $table->foreignId('driver_id')
                ->constrained('drivers')
                ->restrictOnDelete();

            $table->string('deduction_type', 20);

            $table->unsignedBigInteger('deductible_record_id');

            $table->decimal('amount', 12, 2);

            $table->timestamps();

            $table->index(
                ['deduction_type', 'deductible_record_id'],
                'payroll_allocation_record_index'
            );

            $table->index(
                ['driver_id', 'deduction_type'],
                'payroll_allocation_driver_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_deduction_allocations');
    }
};
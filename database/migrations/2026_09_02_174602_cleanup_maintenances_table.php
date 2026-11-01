<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = Schema::getColumnListing('maintenances');

        Schema::table('maintenances', function (Blueprint $table) use ($columns) {
            if (!in_array('service_type', $columns) && !in_array('repair_type', $columns)) {
                $table->string('repair_type')->nullable();
            }

            if (in_array('service_type', $columns) && !in_array('repair_type', $columns)) {
                $table->renameColumn('service_type', 'repair_type');
            }

            if (in_array('cost', $columns) && !in_array('total_cost', $columns)) {
                $table->renameColumn('cost', 'total_cost');
            }

            if (in_array('notes', $columns) && !in_array('remarks', $columns)) {
                $table->renameColumn('notes', 'remarks');
            }

            if (!in_array('maintenance_no', $columns)) {
                $table->string('maintenance_no')->nullable()->unique();
            }

            if (!in_array('parts_cost', $columns)) {
                $table->decimal('parts_cost', 10, 2)->default(0);
            }

            if (!in_array('labour_cost', $columns)) {
                $table->decimal('labour_cost', 10, 2)->default(0);
            }

            if (!in_array('other_cost', $columns)) {
                $table->decimal('other_cost', 10, 2)->default(0);
            }

            if (!in_array('total_cost', $columns) && !in_array('cost', $columns)) {
                $table->decimal('total_cost', 10, 2)->default(0);
            }

            if (!in_array('odometer', $columns)) {
                $table->unsignedInteger('odometer')->nullable();
            }

            if (!in_array('out_of_service_start', $columns)) {
                $table->date('out_of_service_start')->nullable();
            }

            if (!in_array('out_of_service_end', $columns)) {
                $table->date('out_of_service_end')->nullable();
            }

            if (!in_array('invoice_receipt', $columns)) {
                $table->string('invoice_receipt')->nullable();
            }

            if (!in_array('status', $columns)) {
                $table->string('status')->default('Open');
            }

            if (!in_array('remarks', $columns)) {
                $table->text('remarks')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Keep existing maintenance data intact.
    }
};
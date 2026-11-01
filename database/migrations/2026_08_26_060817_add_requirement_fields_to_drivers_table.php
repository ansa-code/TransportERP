<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = Schema::getColumnListing('drivers');

        Schema::table('drivers', function (Blueprint $table) use ($columns) {
            if (!in_array('driver_code', $columns)) {
                $table->string('driver_code')->nullable()->unique();
            }

            if (!in_array('nationality', $columns)) {
                $table->string('nationality')->nullable();
            }

            if (!in_array('passport_number', $columns)) {
                $table->string('passport_number')->nullable();
            }

            if (!in_array('passport_held_by_company', $columns)) {
                $table->boolean('passport_held_by_company')->default(false);
            }

            if (!in_array('visa_type', $columns)) {
                $table->string('visa_type')->nullable();
            }

            if (!in_array('visa_provided_by', $columns)) {
                $table->string('visa_provided_by')->nullable();
            }

            if (!in_array('visa_expiry', $columns)) {
                $table->date('visa_expiry')->nullable();
            }

            if (!in_array('emirates_id', $columns)) {
                $table->string('emirates_id')->nullable();
            }

            if (!in_array('basic_salary', $columns)) {
                $table->decimal('basic_salary', 12, 2)->default(0);
            }

            if (!in_array('assigned_vehicle_id', $columns)) {
                $table->foreignId('assigned_vehicle_id')
                    ->nullable()
                    ->constrained('vehicles')
                    ->nullOnDelete();
            }

            if (!in_array('employment_status', $columns)) {
                $table->string('employment_status')->default('Active');
            }

            if (!in_array('remarks', $columns)) {
                $table->text('remarks')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Keep existing driver data intact.
    }
};
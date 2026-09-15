<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();

            $table->foreignId('driver_id')
                ->constrained('drivers')
                ->restrictOnDelete();

            $table->enum('leave_type', [
                'Annual',
                'Sick',
                'Emergency',
                'Unpaid',
                'Other',
            ]);

            $table->date('start_date');

            $table->date('end_date');

            $table->enum('approval_status', [
                'Pending',
                'Approved',
                'Rejected',
                'Cancelled',
            ])->default('Pending');

            $table->foreignId('replacement_driver_id')
                ->nullable()
                ->constrained('drivers')
                ->nullOnDelete();

            $table->text('approval_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
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
        Schema::create('traffic_fines', function (Blueprint $table) {
            $table->id();

            // Fine identification
            $table->string('fine_number')->unique();

            // Vehicle / Driver references
            $table->foreignId('vehicle_id')
                ->nullable()
                ->constrained('vehicles')
                ->nullOnDelete();

            $table->foreignId('driver_id')
                ->nullable()
                ->constrained('drivers')
                ->nullOnDelete();

            // Fine information
            $table->date('fine_date');
            $table->decimal('amount', 12, 2);

            $table->string('reason_location')->nullable();

            // Payment status
            $table->enum('payment_status', [
                'Unpaid',
                'Paid',
                'Deducted',
                'Cancelled',
            ])->default('Unpaid');

            // Driver deduction
            $table->boolean('deduct_from_driver')->default(false);

            // Payment information
            $table->date('paid_date')->nullable();
            $table->string('paid_reference')->nullable();

            // Supporting document
            $table->string('attachment')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traffic_fines');
    }
};
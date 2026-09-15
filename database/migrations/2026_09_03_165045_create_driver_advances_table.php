<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_advances', function (Blueprint $table) {
            $table->id();

            $table->string('advance_no')->unique();

            $table->foreignId('driver_id')
                ->constrained('drivers')
                ->restrictOnDelete();

            $table->date('advance_date');

            $table->decimal('amount', 12, 2);

            $table->text('reason')->nullable();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->decimal('deducted_amount', 12, 2)
                ->default(0);

            $table->decimal('remaining_amount', 12, 2)
                ->default(0);

            $table->enum('status', [
                'Pending',
                'Approved',
                'Partially Deducted',
                'Fully Deducted',
                'Rejected',
            ])->default('Pending');

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_advances');
    }
};
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
        Schema::create('fuel_recoveries', function (Blueprint $table) {

            $table->id();

            $table->foreignId('fuel_id')
                ->constrained('fuels')
                ->cascadeOnDelete();

            $table->foreignId('invoice_id')
                ->constrained('invoices')
                ->restrictOnDelete();

            $table->decimal('recovery_amount', 12, 2);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Prevent the same fuel entry from being recovered
            | more than once in the same invoice.
            |--------------------------------------------------------------------------
            */
            $table->unique([
                'fuel_id',
                'invoice_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_recoveries');
    }
};
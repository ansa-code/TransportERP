<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_trips', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')
                ->constrained('invoices')
                ->cascadeOnDelete();

            $table->foreignId('trip_id')
                ->constrained('trips')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['invoice_id', 'trip_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_trips');
    }
};
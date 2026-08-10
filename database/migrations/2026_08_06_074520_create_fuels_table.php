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
        Schema::create('fuels', function (Blueprint $table) {

    $table->id();

    $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');

    $table->foreignId('driver_id')->constrained()->onDelete('cascade');

    $table->date('fuel_date');

    $table->decimal('liters',8,2);

    $table->decimal('price_per_liter',10,2);

    $table->decimal('total_amount',10,2);

    $table->integer('odometer');

    $table->string('fuel_station');

    $table->text('notes')->nullable();

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuels');
    }
};

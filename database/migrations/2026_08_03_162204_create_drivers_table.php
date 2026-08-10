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
    Schema::create('drivers', function (Blueprint $table) {

        $table->id();

        $table->string('driver_name');

        $table->string('cnic')->unique();

        $table->string('license_number')->unique();

        $table->date('license_expiry');

        $table->string('phone');

        $table->string('address');

        $table->date('date_of_birth');

        $table->date('joining_date');

        $table->string('status')->default('Active');

        $table->text('notes')->nullable();

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};

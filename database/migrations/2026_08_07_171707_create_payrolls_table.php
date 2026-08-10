<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {

            $table->id();

            $table->foreignId('driver_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->string('salary_month');

            $table->decimal('basic_salary', 10, 2);

            $table->decimal('allowance', 10, 2)->default(0);

            $table->decimal('deduction', 10, 2)->default(0);

            $table->decimal('net_salary', 10, 2);

            $table->date('payment_date')->nullable();

            $table->string('status')->default('Pending');

            $table->text('notes')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
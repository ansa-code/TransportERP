<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')
                ->constrained('invoices')
                ->cascadeOnDelete();

            $table->foreignId('assignment_id')
                ->constrained('assignments')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['invoice_id', 'assignment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_assignments');
    }
};
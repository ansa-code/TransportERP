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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // User who performed the action
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Action details
            $table->string('action');
            $table->string('module')->nullable();

            // Record affected by the action
            $table->string('record_type')->nullable();
            $table->unsignedBigInteger('record_id')->nullable();

            // Human-readable description
            $table->text('description')->nullable();

            // Previous and new data for audit comparison
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Request/context information
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            // Useful indexes for audit searches
            $table->index(['module', 'action']);
            $table->index(['record_type', 'record_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
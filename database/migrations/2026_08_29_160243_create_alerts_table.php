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
        Schema::create('alerts', function (Blueprint $table) {

            $table->id();

            // Alert kis module se related hai
            $table->string('module');

            // Alert kis record se related hai
            $table->unsignedBigInteger('record_id')->nullable();

            // Alert heading
            $table->string('title');

            // Detailed message
            $table->text('message');

            // warning / danger / info / success
            $table->string('type')->default('warning');

            // unread / read
            $table->string('status')->default('unread');

            // Alert kab expire/relevant hota hai
            $table->date('alert_date')->nullable();

            // User ne alert acknowledge kiya ya nahi
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            // Faster filtering
            $table->index(['module', 'record_id']);
            $table->index(['status']);
            $table->index(['alert_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};

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
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('subject_type')->nullable()->after('description');
            $table->unsignedBigInteger('subject_id')->nullable()->after('subject_type');

            $table->index(
                ['subject_type', 'subject_id'],
                'activity_logs_subject_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('activity_logs_subject_index');

            $table->dropColumn([
                'subject_type',
                'subject_id',
            ]);
        });
    }
};
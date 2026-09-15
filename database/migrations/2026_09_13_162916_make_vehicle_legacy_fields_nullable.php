<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->year('manufacture_year')
                ->nullable()
                ->change();

            $table->integer('capacity')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->year('manufacture_year')
                ->nullable(false)
                ->change();

            $table->integer('capacity')
                ->nullable(false)
                ->change();
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {

            $table->decimal('parts_cost', 12, 2)
                ->default(0)
                ->after('amount');

            $table->decimal('labour_cost', 12, 2)
                ->default(0)
                ->after('parts_cost');

            $table->decimal('other_cost', 12, 2)
                ->default(0)
                ->after('labour_cost');

        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {

            $table->dropColumn([
                'parts_cost',
                'labour_cost',
                'other_cost',
            ]);

        });
    }
};
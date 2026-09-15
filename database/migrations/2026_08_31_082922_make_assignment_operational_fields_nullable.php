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
        Schema::table('assignments', function (Blueprint $table) {

            $table->string('pickup_location')
                ->nullable()
                ->change();

            $table->string('drop_location')
                ->nullable()
                ->change();

            $table->date('loading_date')
                ->nullable()
                ->change();

            $table->date('delivery_date')
                ->nullable()
                ->change();

            $table->decimal('freight_amount', 10, 2)
                ->nullable()
                ->change();

            $table->text('notes')
                ->nullable()
                ->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {

            $table->string('pickup_location')
                ->nullable(false)
                ->change();

            $table->string('drop_location')
                ->nullable(false)
                ->change();

            $table->date('loading_date')
                ->nullable(false)
                ->change();

            $table->date('delivery_date')
                ->nullable(false)
                ->change();

            $table->decimal('freight_amount', 10, 2)
                ->nullable(false)
                ->change();

            $table->text('notes')
                ->nullable()
                ->change();

        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
    public function up(): void
{
    Schema::table('trips', function (Blueprint $table) {

        $table->string('trip_no')
            ->unique()
            ->after('id');

        $table->foreignId('assignment_id')
            ->nullable()
            ->after('trip_no')
            ->constrained('assignments')
            ->nullOnDelete();

        $table->foreignId('client_id')
            ->after('assignment_id')
            ->constrained('clients')
            ->cascadeOnDelete();

        $table->foreignId('vehicle_id')
            ->after('client_id')
            ->constrained('vehicles')
            ->cascadeOnDelete();

        $table->foreignId('driver_id')
            ->after('vehicle_id')
            ->constrained('drivers')
            ->cascadeOnDelete();

        $table->dateTime('trip_start')
            ->after('driver_id');

        $table->dateTime('trip_end')
            ->nullable()
            ->after('trip_start');

        $table->string('loading_point')
            ->after('trip_end');

        $table->string('unloading_point')
            ->after('loading_point');

        $table->decimal('rate', 12, 2)
            ->default(0)
            ->after('unloading_point');

        $table->decimal('freight_amount', 12, 2)
            ->default(0)
            ->after('rate');

        $table->string('pod_file')
            ->nullable()
            ->after('freight_amount');

        $table->string('status')
            ->default('Planned')
            ->after('pod_file');

        $table->text('remarks')
            ->nullable()
            ->after('status');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('trips', function (Blueprint $table) {

        $table->dropForeign(['assignment_id']);
        $table->dropForeign(['client_id']);
        $table->dropForeign(['vehicle_id']);
        $table->dropForeign(['driver_id']);

        $table->dropColumn([
            'trip_no',
            'assignment_id',
            'client_id',
            'vehicle_id',
            'driver_id',
            'trip_start',
            'trip_end',
            'loading_point',
            'unloading_point',
            'rate',
            'freight_amount',
            'pod_file',
            'status',
            'remarks',
        ]);

    });
}
};


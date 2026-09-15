<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {

            $table->id();

            // Payment Number
            $table->string('payment_no')->unique();

            // Client
            $table->foreignId('client_id')
                ->constrained('clients')
                ->onDelete('cascade');

            // Payment Date
            $table->date('payment_date');

            // Total Payment Amount
            $table->decimal('amount', 12, 2);

            // Payment Method
            $table->enum('payment_method', [
                'Bank',
                'Cash',
                'Cheque',
                'Other',
            ]);

            // Payment Reference
            $table->string('reference')->nullable();

            // Amount not allocated to invoices
            $table->decimal('unallocated_amount', 12, 2)
                ->default(0);

            // Notes
            $table->text('notes')->nullable();

            // Attachment
            $table->string('attachment')->nullable();

            $table->timestamps();
        });


        /*
        |--------------------------------------------------------------------------
        | Payment Allocations
        |--------------------------------------------------------------------------
        |
        | One payment can be allocated to multiple invoices.
        | One invoice can also receive multiple payments.
        |
        */

        Schema::create('payment_allocations', function (Blueprint $table) {

            $table->id();

            $table->foreignId('payment_id')
                ->constrained('payments')
                ->onDelete('cascade');

            $table->foreignId('invoice_id')
                ->constrained('invoices')
                ->onDelete('cascade');

            // Amount allocated from this payment to this invoice
            $table->decimal('allocated_amount', 12, 2);

            $table->timestamps();

            // Prevent duplicate payment-invoice allocation rows
            $table->unique([
                'payment_id',
                'invoice_id',
            ]);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('payment_allocations');

        Schema::dropIfExists('payments');
    }
};
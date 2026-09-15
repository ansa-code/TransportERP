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
        Schema::create('documents', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Document Information
            |--------------------------------------------------------------------------
            */

            $table->string('document_type');
            $table->string('document_number')->nullable();

            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Related Entity
            |--------------------------------------------------------------------------
            |
            | A document can belong to a Vehicle, Driver, Client or Vendor.
            |
            */

            $table->string('related_type');
            $table->unsignedBigInteger('related_id');

            /*
            |--------------------------------------------------------------------------
            | File Information
            |--------------------------------------------------------------------------
            */

            $table->string('file_path');
            $table->string('original_file_name')->nullable();
            $table->string('file_mime_type')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Document Status
            |--------------------------------------------------------------------------
            */

            $table->string('status')->default('active');

            /*
            |--------------------------------------------------------------------------
            | Version History
            |--------------------------------------------------------------------------
            |
            | When a document is replaced, the new version can point
            | back to the previous document record.
            |
            */

            $table->unsignedBigInteger('parent_document_id')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_current')->default(true);

            /*
            |--------------------------------------------------------------------------
            | Notes
            |--------------------------------------------------------------------------
            */

            $table->text('notes')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index(
                ['related_type', 'related_id'],
                'documents_related_entity_index'
            );

            $table->index(
                'document_type',
                'documents_document_type_index'
            );

            $table->index(
                'expiry_date',
                'documents_expiry_date_index'
            );

            $table->index(
                'status',
                'documents_status_index'
            );

            $table->index(
                ['parent_document_id', 'version'],
                'documents_version_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
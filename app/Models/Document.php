<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    protected $fillable = [
        'document_type',
        'document_number',
        'issue_date',
        'expiry_date',
        'related_type',
        'related_id',
        'file_path',
        'original_file_name',
        'file_mime_type',
        'status',
        'parent_document_id',
        'version',
        'is_current',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'version' => 'integer',
        'is_current' => 'boolean',
    ];

    /**
     * Related Vehicle / Driver / Client / Vendor.
     */
    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Previous document version.
     */
    public function parentDocument(): BelongsTo
    {
        return $this->belongsTo(
            Document::class,
            'parent_document_id'
        );
    }

    /**
     * Replacement/current document versions.
     */
    public function versions()
    {
        return $this->hasMany(
            Document::class,
            'parent_document_id'
        )->orderBy('version');
    }
}
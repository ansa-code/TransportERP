<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Create an activity log entry.
     */
    public function log(
        string $action,
        string $module,
        string $description,
        ?Model $subject = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => Auth::id(),

            'action' => $action,
            'module' => $module,
            'description' => $description,

            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),

            'old_values' => $oldValues,
            'new_values' => $newValues,

            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Log a newly created record.
     */
    public function created(
        string $module,
        Model $subject,
        ?string $description = null,
        ?array $newValues = null
    ): ActivityLog {
        return $this->log(
            action: 'created',
            module: $module,
            description: $description ?? "Created {$module} record.",
            subject: $subject,
            newValues: $newValues ?? $subject->toArray()
        );
    }

    /**
     * Log an updated record.
     */
    public function updated(
        string $module,
        Model $subject,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): ActivityLog {
        return $this->log(
            action: 'updated',
            module: $module,
            description: $description ?? "Updated {$module} record.",
            subject: $subject,
            oldValues: $oldValues,
            newValues: $newValues ?? $subject->toArray()
        );
    }

    /**
     * Log a deleted record.
     */
    public function deleted(
        string $module,
        Model $subject,
        ?array $oldValues = null,
        ?string $description = null
    ): ActivityLog {
        return $this->log(
            action: 'deleted',
            module: $module,
            description: $description ?? "Deleted {$module} record.",
            subject: $subject,
            oldValues: $oldValues ?? $subject->toArray()
        );
    }

    /**
     * Log a custom activity.
     */
    public function custom(
        string $action,
        string $module,
        string $description,
        ?Model $subject = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): ActivityLog {
        return $this->log(
            action: $action,
            module: $module,
            description: $description,
            subject: $subject,
            oldValues: $oldValues,
            newValues: $newValues
        );
    }
}
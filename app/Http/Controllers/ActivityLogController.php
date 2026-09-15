<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Activity Log / Audit Trail List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = $request->search;
        $action = $request->action;
        $module = $request->module;

        $logsQuery = ActivityLog::with('user')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                        ->orWhere('action', 'like', "%{$search}%")
                        ->orWhere('module', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($action, function ($query, $action) {
                $query->where('action', $action);
            })
            ->when($module, function ($query, $module) {
                $query->where('module', $module);
            });

        $totalLogs = (clone $logsQuery)->count();

        $logs = $logsQuery
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $actions = ActivityLog::query()
            ->whereNotNull('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $modules = ActivityLog::query()
            ->whereNotNull('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        return view(
            'activity_logs.index',
            compact(
                'logs',
                'search',
                'action',
                'module',
                'actions',
                'modules',
                'totalLogs'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Show Activity Log
    |--------------------------------------------------------------------------
    */

    public function show(ActivityLog $activityLog)
    {
        $activityLog->load([
            'user',
            'subject',
        ]);

        return view(
            'activity_logs.show',
            compact('activityLog')
        );
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\ErrorLog;
use Illuminate\Http\Request;

class ErrorLogController extends Controller
{
    /**
     * Display all error logs.
     */
    public function index(Request $request)
    {
        $query = ErrorLog::with(['user', 'resolvedBy'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                    ->orWhere('exception_type', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'resolved') {
                $query->where('is_resolved', true);
            }

            if ($request->status === 'unresolved') {
                $query->where('is_resolved', false);
            }
        }

        // Show ALL matching error logs on one page.
        $errorLogs = $query->get();

        return view('error-logs.index', compact('errorLogs'));
    }

    /**
     * Display a single error log.
     */
    public function show(string $id)
    {
        $errorLog = ErrorLog::with(['user', 'resolvedBy'])
            ->findOrFail($id);

        return view('error-logs.show', compact('errorLog'));
    }

    /**
     * Mark an error log as resolved.
     */
    public function resolve(string $id)
    {
        $errorLog = ErrorLog::findOrFail($id);

        $errorLog->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => auth()->id(),
        ]);

        return redirect()
            ->route('error-logs.show', $errorLog->id)
            ->with('success', 'Error log marked as resolved.');
    }

    /**
     * Mark an error log as unresolved.
     */
    public function reopen(string $id)
    {
        $errorLog = ErrorLog::findOrFail($id);

        $errorLog->update([
            'is_resolved' => false,
            'resolved_at' => null,
            'resolved_by' => null,
        ]);

        return redirect()
            ->route('error-logs.show', $errorLog->id)
            ->with('success', 'Error log marked as unresolved.');
    }
}
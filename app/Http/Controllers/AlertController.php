<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Services\AlertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AlertController extends Controller
{
    public function index(AlertService $alertService): View
    {
        /*
        |--------------------------------------------------------------------------
        | Generate latest alerts before displaying the Alerts Center
        |--------------------------------------------------------------------------
        */
        $alertService->generateAlerts();

        $alerts = Alert::orderByDesc('created_at')->get();

        return view('alerts.index', compact('alerts'));
    }

    public function markAsRead(Alert $alert): RedirectResponse
    {
        $alert->update([
            'status' => 'read',
            'read_at' => now(),
        ]);

        return redirect()
            ->route('alerts.index')
            ->with('success', 'Alert marked as read.');
    }

    public function markAllAsRead(): RedirectResponse
    {
        Alert::where('status', 'unread')
            ->update([
                'status' => 'read',
                'read_at' => now(),
            ]);

        return redirect()
            ->route('alerts.index')
            ->with('success', 'All alerts marked as read.');
    }
}
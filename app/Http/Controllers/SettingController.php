<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display Settings page.
     */
    public function index()
    {
        $settings = [
            'base_currency' => Setting::where('key', 'base_currency')
                ->value('value') ?? 'AED',

            'vat_enabled' => Setting::where('key', 'vat_enabled')
                ->value('value') ?? '1',

            'vat_rate' => Setting::where('key', 'vat_rate')
                ->value('value') ?? '0',
        ];

        return view('settings.index', compact('settings'));
    }

    /**
     * Update Settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'base_currency' => [
                'required',
                'string',
                'max:10',
            ],

            'vat_enabled' => [
                'required',
                'boolean',
            ],

            'vat_rate' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],
        ]);

        Setting::updateOrCreate(
            ['key' => 'base_currency'],
            ['value' => strtoupper($validated['base_currency'])]
        );

        Setting::updateOrCreate(
            ['key' => 'vat_enabled'],
            ['value' => $validated['vat_enabled'] ? '1' : '0']
        );

        Setting::updateOrCreate(
            ['key' => 'vat_rate'],
            ['value' => $validated['vat_rate']]
        );

        return redirect()
            ->route('settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
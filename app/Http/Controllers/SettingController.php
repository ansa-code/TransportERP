<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'base_currency' => Setting::where('key', 'base_currency')->value('value') ?? 'AED',
            'vat_enabled' => Setting::where('key', 'vat_enabled')->value('value') ?? '1',
            'vat_rate' => Setting::where('key', 'vat_rate')->value('value') ?? '0',

            // Driver Licence Settings
            'driver_license_restriction' => Setting::where('key', 'driver_license_restriction')->value('value') ?? '1',
            'driver_license_warning' => Setting::where('key', 'driver_license_warning')->value('value') ?? '1',
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'base_currency' => ['required', 'string', 'max:10'],
            'vat_enabled' => ['required', 'boolean'],
            'vat_rate' => ['required', 'numeric', 'min:0', 'max:100'],

            // Driver Licence Settings
            'driver_license_restriction' => ['required', 'boolean'],
            'driver_license_warning' => ['required', 'boolean'],
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

        Setting::updateOrCreate(
            ['key' => 'driver_license_restriction'],
            ['value' => $validated['driver_license_restriction'] ? '1' : '0']
        );

        Setting::updateOrCreate(
            ['key' => 'driver_license_warning'],
            ['value' => $validated['driver_license_warning'] ? '1' : '0']
        );

        return redirect()
            ->route('settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
<?php

namespace App\Services;

use App\Models\Driver;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class DriverLicenseValidationService
{
    public function check(
        int|string $driverId,
        string $assignmentStartDate
    ): ?string {
        $driver = Driver::find($driverId);

        if (!$driver || !$driver->license_expiry) {
            return null;
        }

        // Licence must be valid on the assignment start date.
        // Expiry date equal to assignment start date is also expired.
        $expiryDate = $driver->license_expiry->startOfDay();

        $startDate = Carbon::parse($assignmentStartDate)->startOfDay();

        if ($expiryDate->gt($startDate)) {
            return null;
        }

        $restriction = Setting::where(
            'key',
            'driver_license_restriction'
        )->value('value') ?? '1';

        $warning = Setting::where(
            'key',
            'driver_license_warning'
        )->value('value') ?? '1';

        $message = "The selected driver's driving licence has expired. Please update the licence details or select another driver.";

        // Restriction takes priority over warning.
        if ((string) $restriction === '1') {
            throw ValidationException::withMessages([
                'driver_id' => $message,
            ]);
        }

        // Allow allocation but return warning.
        if ((string) $warning === '1') {
            return 'Warning: ' . $message;
        }

        // Both settings disabled: allow without warning.
        return null;
    }
}
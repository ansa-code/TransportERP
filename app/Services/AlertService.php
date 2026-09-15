<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\Client;
use App\Models\Document;
use App\Models\Invoice;
use App\Models\Leave;
use App\Models\Maintenance;
use App\Models\Payroll;
use App\Models\Vehicle;
use Carbon\Carbon;

class AlertService
{
    public function generateAlerts(): void
    {
        $this->vehicleExpiryAlerts();
        $this->documentExpiryAlerts();
        $this->contractExpiryAlerts();
        $this->invoiceDueAlerts();
        $this->payrollPendingAlerts();
        $this->driverLeaveAlerts();
        $this->vehicleMaintenanceAlerts();
    }

    /*
    |--------------------------------------------------------------------------
    | Vehicle Insurance / Registration Expiry
    |--------------------------------------------------------------------------
    */

    private function vehicleExpiryAlerts(): void
    {
        $today = Carbon::today();

        $vehicles = Vehicle::query()
            ->where(function ($query) use ($today) {
                $query->whereNotNull('insurance_expiry')
                    ->where(
                        'insurance_expiry',
                        '<=',
                        $today->copy()->addDays(30)
                    );
            })
            ->orWhere(function ($query) use ($today) {
                $query->whereNotNull('registration_expiry')
                    ->where(
                        'registration_expiry',
                        '<=',
                        $today->copy()->addDays(30)
                    );
            })
            ->get();

        foreach ($vehicles as $vehicle) {

            if ($vehicle->insurance_expiry) {
                $this->createVehicleExpiryAlert(
                    $vehicle,
                    'Insurance',
                    $vehicle->insurance_expiry
                );
            }

            if ($vehicle->registration_expiry) {
                $this->createVehicleExpiryAlert(
                    $vehicle,
                    'Registration',
                    $vehicle->registration_expiry
                );
            }
        }
    }

    private function createVehicleExpiryAlert(
        Vehicle $vehicle,
        string $document,
        $expiryDate
    ): void {
        $today = Carbon::today();
        $expiryDate = Carbon::parse($expiryDate);

        $daysRemaining = $today->diffInDays(
            $expiryDate,
            false
        );

        if ($daysRemaining > 30) {
            return;
        }

        $vehicleName =
            $vehicle->plate_number
            ?? $vehicle->vehicle_code
            ?? 'Unknown Vehicle';

        if ($daysRemaining < 0) {

            $title = $document . ' Expired';

            $message =
                'Vehicle ' .
                $vehicleName .
                ' has an expired ' .
                strtolower($document) .
                '.';

            $type = 'danger';

        } elseif ($daysRemaining === 0) {

            $title = $document . ' Expires Today';

            $message =
                'Vehicle ' .
                $vehicleName .
                '\'s ' .
                strtolower($document) .
                ' expires today.';

            $type = 'danger';

        } elseif ($daysRemaining <= 7) {

            $title =
                $document .
                ' Expiring in ' .
                $daysRemaining .
                ' Days';

            $message =
                'Vehicle ' .
                $vehicleName .
                '\'s ' .
                strtolower($document) .
                ' expires in ' .
                $daysRemaining .
                ' day(s).';

            $type = 'warning';

        } elseif ($daysRemaining <= 15) {

            $title = $document . ' Expiring in 15 Days';

            $message =
                'Vehicle ' .
                $vehicleName .
                '\'s ' .
                strtolower($document) .
                ' expires within 15 days.';

            $type = 'warning';

        } else {

            $title = $document . ' Expiring in 30 Days';

            $message =
                'Vehicle ' .
                $vehicleName .
                '\'s ' .
                strtolower($document) .
                ' expires within 30 days.';

            $type = 'warning';
        }

        $this->upsertAlert(
            module: 'Vehicles',
            recordId: $vehicle->id,
            title: $title,
            message: $message,
            type: $type,
            alertDate: $expiryDate
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Documents Expiry Center Alerts
    |--------------------------------------------------------------------------
    */

    private function documentExpiryAlerts(): void
    {
        $today = Carbon::today();

        $documents = Document::query()
            ->where('is_current', true)
            ->whereNotNull('expiry_date')
            ->whereDate(
                'expiry_date',
                '<=',
                $today->copy()->addDays(30)
            )
            ->get();

        foreach ($documents as $document) {

            $expiryDate = Carbon::parse(
                $document->expiry_date
            );

            $daysRemaining = $today->diffInDays(
                $expiryDate,
                false
            );

            $documentName =
                $document->document_type
                ?? 'Document';

            $documentNumber =
                $document->document_number
                ? ' #' . $document->document_number
                : '';

            if ($daysRemaining < 0) {

                $title =
                    $documentName .
                    $documentNumber .
                    ' Expired';

                $message =
                    $documentName .
                    $documentNumber .
                    ' has expired.';

                $type = 'danger';

            } elseif ($daysRemaining === 0) {

                $title =
                    $documentName .
                    $documentNumber .
                    ' Expires Today';

                $message =
                    $documentName .
                    $documentNumber .
                    ' expires today.';

                $type = 'danger';

            } elseif ($daysRemaining <= 7) {

                $title =
                    $documentName .
                    $documentNumber .
                    ' Expiring in ' .
                    $daysRemaining .
                    ' Days';

                $message =
                    $documentName .
                    $documentNumber .
                    ' expires in ' .
                    $daysRemaining .
                    ' day(s).';

                $type = 'warning';

            } elseif ($daysRemaining <= 15) {

                $title =
                    $documentName .
                    $documentNumber .
                    ' Expiring in 15 Days';

                $message =
                    $documentName .
                    $documentNumber .
                    ' expires within 15 days.';

                $type = 'warning';

            } else {

                $title =
                    $documentName .
                    $documentNumber .
                    ' Expiring in 30 Days';

                $message =
                    $documentName .
                    $documentNumber .
                    ' expires within 30 days.';

                $type = 'warning';
            }

            $this->upsertAlert(
                module: 'Documents',
                recordId: $document->id,
                title: $title,
                message: $message,
                type: $type,
                alertDate: $expiryDate
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Client Contract Expiry
    |--------------------------------------------------------------------------
    */

    private function contractExpiryAlerts(): void
    {
        $today = Carbon::today();

        $clients = Client::query()
            ->whereNotNull('contract_end')
            ->where(
                'contract_end',
                '<=',
                $today->copy()->addDays(30)
            )
            ->get();

        foreach ($clients as $client) {

            $contractEnd = Carbon::parse(
                $client->contract_end
            );

            $daysRemaining = $today->diffInDays(
                $contractEnd,
                false
            );

            $clientName =
                $client->client_name
                ?? 'Unknown Client';

            if ($daysRemaining < 0) {

                $title = 'Contract Expired';

                $message =
                    'Client contract for ' .
                    $clientName .
                    ' has expired.';

                $type = 'danger';

            } elseif ($daysRemaining === 0) {

                $title = 'Contract Expires Today';

                $message =
                    'Client contract for ' .
                    $clientName .
                    ' expires today.';

                $type = 'danger';

            } elseif ($daysRemaining <= 7) {

                $title =
                    'Contract Expiring in ' .
                    $daysRemaining .
                    ' Days';

                $message =
                    'Client contract for ' .
                    $clientName .
                    ' expires in ' .
                    $daysRemaining .
                    ' day(s).';

                $type = 'warning';

            } elseif ($daysRemaining <= 15) {

                $title =
                    'Contract Expiring in 15 Days';

                $message =
                    'Client contract for ' .
                    $clientName .
                    ' expires within 15 days.';

                $type = 'warning';

            } else {

                $title =
                    'Contract Expiring in 30 Days';

                $message =
                    'Client contract for ' .
                    $clientName .
                    ' expires within 30 days.';

                $type = 'warning';
            }

            $this->upsertAlert(
                module: 'Clients',
                recordId: $client->id,
                title: $title,
                message: $message,
                type: $type,
                alertDate: $contractEnd
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Invoice Due / Overdue
    |--------------------------------------------------------------------------
    */

    private function invoiceDueAlerts(): void
    {
        $today = Carbon::today();

        $invoices = Invoice::query()
            ->whereNotNull('due_date')
            ->where('balance', '>', 0)
            ->where(
                'due_date',
                '<=',
                $today->copy()->addDays(7)
            )
            ->get();

        foreach ($invoices as $invoice) {

            $dueDate = Carbon::parse(
                $invoice->due_date
            );

            $daysRemaining = $today->diffInDays(
                $dueDate,
                false
            );

            $invoiceNumber =
                $invoice->invoice_number
                ?? $invoice->invoice_no
                ?? ('Invoice #' . $invoice->id);

            if ($daysRemaining < 0) {

                $title = 'Invoice Overdue';

                $message =
                    $invoiceNumber .
                    ' is overdue with an outstanding balance of ' .
                    number_format(
                        (float) $invoice->balance,
                        2
                    ) .
                    '.';

                $type = 'danger';

            } elseif ($daysRemaining === 0) {

                $title = 'Invoice Due Today';

                $message =
                    $invoiceNumber .
                    ' is due today with an outstanding balance of ' .
                    number_format(
                        (float) $invoice->balance,
                        2
                    ) .
                    '.';

                $type = 'warning';

            } else {

                $title =
                    'Invoice Due in ' .
                    $daysRemaining .
                    ' Days';

                $message =
                    $invoiceNumber .
                    ' is due in ' .
                    $daysRemaining .
                    ' day(s) with an outstanding balance of ' .
                    number_format(
                        (float) $invoice->balance,
                        2
                    ) .
                    '.';

                $type = 'warning';
            }

            $this->upsertAlert(
                module: 'Invoices',
                recordId: $invoice->id,
                title: $title,
                message: $message,
                type: $type,
                alertDate: $dueDate
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Payroll Pending
    |--------------------------------------------------------------------------
    */

    private function payrollPendingAlerts(): void
    {
        $payrolls = Payroll::query()
            ->where(function ($query) {
                $query->where(
                    'payment_status',
                    'pending'
                )->orWhere(
                    'status',
                    'pending'
                );
            })
            ->get();

        foreach ($payrolls as $payroll) {

            $this->upsertAlert(
                module: 'Payroll',
                recordId: $payroll->id,
                title: 'Payroll Pending',
                message:
                    'Payroll record #' .
                    $payroll->id .
                    ' is still pending payment.',
                type: 'warning',
                alertDate: $payroll->created_at
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Driver Leave
    |--------------------------------------------------------------------------
    */

    private function driverLeaveAlerts(): void
    {
        $today = Carbon::today();

        $leaves = Leave::query()
            ->where(
                'approval_status',
                'approved'
            )
            ->where(function ($query) use ($today) {

                $query
                    ->whereDate(
                        'start_date',
                        '<=',
                        $today->copy()->addDays(7)
                    )
                    ->whereDate(
                        'end_date',
                        '>=',
                        $today
                    );
            })
            ->get();

        foreach ($leaves as $leave) {

            $startDate = Carbon::parse(
                $leave->start_date
            );

            $endDate = Carbon::parse(
                $leave->end_date
            );

            $this->upsertAlert(
                module: 'Leaves',
                recordId: $leave->id,
                title: 'Driver Leave',
                message:
                    'Approved driver leave is active or starting soon from ' .
                    $startDate->format('d M Y') .
                    ' to ' .
                    $endDate->format('d M Y') .
                    '.',
                type: 'warning',
                alertDate: $startDate
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Vehicle Maintenance
    |--------------------------------------------------------------------------
    */

    private function vehicleMaintenanceAlerts(): void
    {
        $today = Carbon::today();

        $maintenances = Maintenance::query()
            ->whereNotNull('next_service_date')
            ->whereDate(
                'next_service_date',
                '<=',
                $today->copy()->addDays(7)
            )
            ->get();

        foreach ($maintenances as $maintenance) {

            $serviceDate = Carbon::parse(
                $maintenance->next_service_date
            );

            $daysRemaining = $today->diffInDays(
                $serviceDate,
                false
            );

            if ($daysRemaining < 0) {

                $title =
                    'Vehicle Maintenance Overdue';

                $message =
                    'A vehicle maintenance service is overdue since ' .
                    $serviceDate->format('d M Y') .
                    '.';

                $type = 'danger';

            } elseif ($daysRemaining === 0) {

                $title =
                    'Vehicle Maintenance Due Today';

                $message =
                    'A vehicle maintenance service is due today.';

                $type = 'danger';

            } else {

                $title =
                    'Vehicle Maintenance Due in ' .
                    $daysRemaining .
                    ' Days';

                $message =
                    'A vehicle maintenance service is due in ' .
                    $daysRemaining .
                    ' day(s).';

                $type = 'warning';
            }

            $this->upsertAlert(
                module: 'Maintenance',
                recordId: $maintenance->id,
                title: $title,
                message: $message,
                type: $type,
                alertDate: $serviceDate
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Alert Upsert
    |--------------------------------------------------------------------------
    */

    private function upsertAlert(
        string $module,
        int $recordId,
        string $title,
        string $message,
        string $type,
        $alertDate
    ): void {
        $alert = Alert::firstOrNew([
            'module' => $module,
            'record_id' => $recordId,
            'title' => $title,
        ]);

        $wasExisting = $alert->exists;

        $alert->message = $message;
        $alert->type = $type;
        $alert->alert_date = Carbon::parse(
            $alertDate
        )->toDateString();

        if (! $wasExisting) {
            $alert->status = 'unread';
            $alert->read_at = null;
        }

        $alert->save();
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function unreadCount(): int
    {
        return Alert::where(
            'status',
            'unread'
        )->count();
    }

    public function getAlerts()
    {
        return Alert::orderByDesc(
            'created_at'
        )->get();
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Vehicle;
use App\Models\Fuel;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaintenanceController extends Controller
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /*
    |--------------------------------------------------------------------------
    | Maintenance List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = $request->search;
        $repairType = $request->repair_type;

        $maintenances = Maintenance::with('vehicle')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {

                    $q->where(
                        'maintenance_no',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'repair_type',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'workshop',
                            'like',
                            "%{$search}%"
                        );

                    $q->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                        $vehicleQuery->where(
                            'plate_number',
                            'like',
                            "%{$search}%"
                        );
                    });
                });
            })
            ->when($repairType, function ($query) use ($repairType) {
                $query->where(
                    'repair_type',
                    'like',
                    "%{$repairType}%"
                );
            })
            ->latest('maintenance_date')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Repair Type Filter Options
        |--------------------------------------------------------------------------
        */

        $repairTypes = Maintenance::query()
            ->whereNotNull('repair_type')
            ->where(
                'repair_type',
                '!=',
                ''
            )
            ->orderBy('repair_type')
            ->pluck('repair_type')
            ->flatMap(function ($types) {

                return collect(
                    explode(',', $types)
                )->map(function ($type) {

                    return trim($type);
                });

            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | KPI - Open Repairs
        |--------------------------------------------------------------------------
        */

        $openRepairs = Maintenance::whereIn('status', [
            'Open',
            'In Progress',
        ])->count();

        /*
        |--------------------------------------------------------------------------
        | KPI - Monthly Repair Cost
        |--------------------------------------------------------------------------
        */

        $monthlyRepairCost = Maintenance::whereBetween(
            'maintenance_date',
            [
                Carbon::now()
                    ->startOfMonth()
                    ->toDateString(),

                Carbon::now()
                    ->endOfMonth()
                    ->toDateString(),
            ]
        )->sum('total_cost');

        /*
        |--------------------------------------------------------------------------
        | KPI - Monthly Fuel Cost
        |--------------------------------------------------------------------------
        */

        $fuelCost = Fuel::whereBetween(
            'fuel_date',
            [
                Carbon::now()
                    ->startOfMonth()
                    ->toDateString(),

                Carbon::now()
                    ->endOfMonth()
                    ->toDateString(),
            ]
        )->sum('total_amount');

        /*
        |--------------------------------------------------------------------------
        | KPI - Average Downtime
        |--------------------------------------------------------------------------
        */

        $downtimeRecords = Maintenance::whereNotNull(
            'out_of_service_start'
        )
            ->whereNotNull('out_of_service_end')
            ->get([
                'out_of_service_start',
                'out_of_service_end',
            ]);

        $averageDowntime = 0;

        if ($downtimeRecords->count() > 0) {

            $totalDowntime = $downtimeRecords->sum(
                function ($maintenance) {

                    $start = Carbon::parse(
                        $maintenance->out_of_service_start
                    );

                    $end = Carbon::parse(
                        $maintenance->out_of_service_end
                    );

                    return $start->diffInMinutes($end);
                }
            );

            $averageDowntime = round(
                ($totalDowntime / 60 / 24)
                / $downtimeRecords->count(),
                1
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Fuel Records
        |--------------------------------------------------------------------------
        */

        $fuelRecordsCount = Fuel::count();

        /*
        |--------------------------------------------------------------------------
        | Fuel Efficiency
        |--------------------------------------------------------------------------
        */

        $fuelEntries = Fuel::query()
            ->whereNotNull('vehicle_id')
            ->whereNotNull('odometer')
            ->where('liters', '>', 0)
            ->orderBy('vehicle_id')
            ->orderBy('odometer')
            ->get([
                'vehicle_id',
                'odometer',
                'liters',
            ]);

        $totalDistance = 0;
        $totalLiters = 0;

        $previousOdometer = [];

        foreach ($fuelEntries as $fuelEntry) {

            $vehicleId = $fuelEntry->vehicle_id;

            $currentOdometer = (float) $fuelEntry->odometer;

            $liters = (float) $fuelEntry->liters;

            if (isset($previousOdometer[$vehicleId])) {

                $distance =
                    $currentOdometer
                    - $previousOdometer[$vehicleId];

                if ($distance > 0) {

                    $totalDistance += $distance;
                    $totalLiters += $liters;
                }
            }

            $previousOdometer[$vehicleId] =
                $currentOdometer;
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Fuel Efficiency
        |--------------------------------------------------------------------------
        */

        $fuelEfficiency = null;

        if (
            $totalDistance > 0
            && $totalLiters > 0
        ) {

            $fuelEfficiency = round(
                $totalDistance / $totalLiters,
                1
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Return Maintenance Index
        |--------------------------------------------------------------------------
        */

        return view(
            'maintenances.index',
            compact(
                'maintenances',
                'search',
                'repairType',
                'repairTypes',
                'openRepairs',
                'monthlyRepairCost',
                'fuelCost',
                'averageDowntime',
                'fuelRecordsCount',
                'fuelEfficiency'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create Maintenance Form
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $vehicles = Vehicle::orderBy('plate_number')->get();

        return view(
            'maintenances.create',
            compact('vehicles')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store Maintenance
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'vehicle_id' => [
                'required',
                'exists:vehicles,id',
            ],

            'maintenance_date' => [
                'required',
                'date',
            ],

            'repair_type' => [
                'required',
                'array',
                'min:1',
            ],

            'repair_type.*' => [
                'required',
                'in:Preventive,Corrective,Breakdown,Tyre,Battery,Washing,Brake,Oil Change,AC Repair,Electrical,Other',
            ],

            'workshop' => [
                'nullable',
                'string',
                'max:255',
            ],

            'parts_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'labour_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'other_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'odometer' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'out_of_service_start' => [
                'nullable',
                'date',
            ],

            'out_of_service_end' => [
                'nullable',
                'date',
                'after_or_equal:out_of_service_start',
            ],

            'invoice_receipt' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],

            'status' => [
                'required',
                'in:Open,In Progress,Completed,Cancelled',
            ],

            'next_service_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $validated['repair_type'] =
            implode(
                ', ',
                $validated['repair_type']
            );

        $validated['maintenance_no'] =
            $this->generateMaintenanceNumber();

        $validated['total_cost'] =
            (float) $validated['parts_cost']
            + (float) $validated['labour_cost']
            + (float) $validated['other_cost'];

        /*
        |--------------------------------------------------------------------------
        | Invoice / Receipt Attachment
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('invoice_receipt')) {

            $validated['invoice_receipt'] =
                $request->file('invoice_receipt')
                    ->store(
                        'maintenances/invoices',
                        'public'
                    );

        } else {

            $validated['invoice_receipt'] = null;
        }

        $maintenance =
            Maintenance::create($validated);

        $this->updateVehicleStatus(
            $maintenance
        );

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->created(
            module: 'Maintenance',
            subject: $maintenance,
            description:
                "Created maintenance record {$maintenance->maintenance_no}.",
            newValues: $maintenance->toArray()
        );

        return redirect()
            ->route('maintenances.index')
            ->with(
                'success',
                'Maintenance Record Added Successfully!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Show Maintenance
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $maintenance = Maintenance::with('vehicle')
            ->findOrFail($id);

        return view(
            'maintenances.show',
            compact('maintenance')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Maintenance Form
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $maintenance =
            Maintenance::findOrFail($id);

        $vehicles =
            Vehicle::orderBy('plate_number')->get();

        return view(
            'maintenances.edit',
            compact(
                'maintenance',
                'vehicles'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Maintenance
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {
        $maintenance =
            Maintenance::findOrFail($id);

        $oldValues =
            $maintenance->getAttributes();

        $validated = $request->validate([

            'vehicle_id' => [
                'required',
                'exists:vehicles,id',
            ],

            'maintenance_date' => [
                'required',
                'date',
            ],

            'repair_type' => [
                'required',
                'array',
                'min:1',
            ],

            'repair_type.*' => [
                'required',
                'in:Preventive,Corrective,Breakdown,Tyre,Battery,Washing,Brake,Oil Change,AC Repair,Electrical,Other',
            ],

            'workshop' => [
                'nullable',
                'string',
                'max:255',
            ],

            'parts_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'labour_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'other_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'odometer' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'out_of_service_start' => [
                'nullable',
                'date',
            ],

            'out_of_service_end' => [
                'nullable',
                'date',
                'after_or_equal:out_of_service_start',
            ],

            'invoice_receipt' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],

            'status' => [
                'required',
                'in:Open,In Progress,Completed,Cancelled',
            ],

            'next_service_date' => [
                'nullable',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        $validated['repair_type'] =
            implode(
                ', ',
                $validated['repair_type']
            );

        $validated['total_cost'] =
            (float) $validated['parts_cost']
            + (float) $validated['labour_cost']
            + (float) $validated['other_cost'];

        /*
        |--------------------------------------------------------------------------
        | Invoice / Receipt Attachment
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('invoice_receipt')) {

            if (
                $maintenance->invoice_receipt
                && Storage::disk('public')->exists(
                    $maintenance->invoice_receipt
                )
            ) {
                Storage::disk('public')->delete(
                    $maintenance->invoice_receipt
                );
            }

            $validated['invoice_receipt'] =
                $request->file('invoice_receipt')
                    ->store(
                        'maintenances/invoices',
                        'public'
                    );

        } else {

            unset($validated['invoice_receipt']);
        }
        /*
        |--------------------------------------------------------------------------
        | Update Maintenance
        |--------------------------------------------------------------------------
        */

        $maintenance->update(
            $validated
        );

        $this->updateVehicleStatus(
            $maintenance
        );

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->updated(
            module: 'Maintenance',
            subject: $maintenance,
            oldValues: $oldValues,
            newValues: $maintenance->getAttributes(),
            description:
                "Updated maintenance record {$maintenance->maintenance_no}."
        );

        return redirect()
            ->route('maintenances.index')
            ->with(
                'success',
                'Maintenance Updated Successfully!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Maintenance
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $maintenance =
            Maintenance::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Capture Values Before Delete
        |--------------------------------------------------------------------------
        */

        $oldValues =
            $maintenance->getAttributes();

        $maintenanceNo =
            $maintenance->maintenance_no;

        $vehicle =
            $maintenance->vehicle;

        /*
        |--------------------------------------------------------------------------
        | Delete Stored Attachment
        |--------------------------------------------------------------------------
        */

        if (
            $maintenance->invoice_receipt
            && Storage::disk('public')->exists(
                $maintenance->invoice_receipt
            )
        ) {
            Storage::disk('public')->delete(
                $maintenance->invoice_receipt
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->deleted(
            module: 'Maintenance',
            subject: $maintenance,
            oldValues: $oldValues,
            description:
                "Deleted maintenance record {$maintenanceNo}."
        );

        $maintenance->delete();

        /*
        |--------------------------------------------------------------------------
        | Restore Vehicle Status
        |--------------------------------------------------------------------------
        */

        if ($vehicle) {

            $vehicle->update([
                'status' => 'Active',
            ]);
        }

        return redirect()
            ->route('maintenances.index')
            ->with(
                'success',
                'Maintenance Deleted Successfully!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Maintenance Number
    |--------------------------------------------------------------------------
    */

    private function generateMaintenanceNumber()
    {
        $year = date('Y');

        $lastMaintenance =
            Maintenance::whereYear(
                'created_at',
                $year
            )
                ->orderByDesc('id')
                ->first();

        $nextNumber = $lastMaintenance
            ? (
                (int) substr(
                    $lastMaintenance->maintenance_no,
                    -5
                )
            ) + 1
            : 1;

        return 'AST-MNT-' .
            $year .
            '-' .
            str_pad(
                $nextNumber,
                5,
                '0',
                STR_PAD_LEFT
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Vehicle Status
    |--------------------------------------------------------------------------
    */

    private function updateVehicleStatus(
        Maintenance $maintenance
    ) {
        $vehicle =
            $maintenance->vehicle;

        if (!$vehicle) {
            return;
        }

        if (in_array(
            $maintenance->status,
            [
                'Open',
                'In Progress',
            ]
        )) {

            $vehicle->update([
                'status' => 'Maintenance',
            ]);

            return;
        }

        if (in_array(
            $maintenance->status,
            [
                'Completed',
                'Cancelled',
            ]
        )) {

            $vehicle->update([
                'status' => 'Active',
            ]);
        }
    }
}
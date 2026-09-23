<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Fuel;
use App\Models\Trip;
use App\Models\Vehicle;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FuelController extends Controller
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $fuels = Fuel::with([
            'vehicle',
            'driver',
            'client',
            'assignment',
            'trip',
        ])
        ->when($search, function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                $q->where('fuel_entry_no', 'like', "%{$search}%")
                    ->orWhere('paid_by', 'like', "%{$search}%")
                    ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                        $vehicleQuery->where(
                            'plate_number',
                            'like',
                            "%{$search}%"
                        );
                    })
                    ->orWhereHas('driver', function ($driverQuery) use ($search) {
                        $driverQuery->where(
                            'driver_name',
                            'like',
                            "%{$search}%"
                        );
                    })
                    ->orWhereHas('client', function ($clientQuery) use ($search) {
                        $clientQuery->where(
                            'client_name',
                            'like',
                            "%{$search}%"
                        );
                    });
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('fuels.index', compact(
            'fuels',
            'search'
        ));
    }

    public function create()
    {
        $vehicles = Vehicle::all();
        $drivers = Driver::all();
        $clients = Client::all();
        $assignments = Assignment::all();
        $trips = Trip::all();

        return view('fuels.create', compact(
            'vehicles',
            'drivers',
            'clients',
            'assignments',
            'trips'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'vehicle_id' => [
                'required',
                'exists:vehicles,id',
            ],

            'driver_id' => [
                'nullable',
                'exists:drivers,id',
            ],

            'client_id' => [
                'nullable',
                'exists:clients,id',
            ],

            'assignment_id' => [
                'nullable',
                'exists:assignments,id',
            ],

            'trip_id' => [
                'nullable',
                'exists:trips,id',
            ],

            'fuel_date' => [
                'required',
                'date',
                'before_or_equal:now',
            ],

            'liters' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'price_per_liter' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'total_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'odometer' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'fuel_station' => [
                'nullable',
                'string',
                'max:255',
            ],

            'paid_by' => [
                'required',
                'in:AL SHAQRA,Client,Driver,Vendor',
            ],

            'reimbursable' => [
                'required',
                'boolean',
            ],

            'reimbursement_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'receipt' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Calculate Total Cost
        |--------------------------------------------------------------------------
        */

        $liters = (float) $validated['liters'];
        $pricePerLiter = (float) ($validated['price_per_liter'] ?? 0);

        $calculatedTotal = round(
            $liters * $pricePerLiter,
            2
        );

        $validated['total_amount'] = $calculatedTotal;

        /*
        |--------------------------------------------------------------------------
        | Reimbursement Validation
        |--------------------------------------------------------------------------
        */

        if ((bool) $validated['reimbursable']) {

            if (
                !isset($validated['reimbursement_amount']) ||
                $validated['reimbursement_amount'] <= 0
            ) {
                return back()
                    ->withErrors([
                        'reimbursement_amount' =>
                            'Reimbursement amount is required when fuel is reimbursable.',
                    ])
                    ->withInput();
            }

            if ($validated['reimbursement_amount'] > $calculatedTotal) {
                return back()
                    ->withErrors([
                        'reimbursement_amount' =>
                            'Reimbursement amount cannot be greater than total fuel cost.',
                    ])
                    ->withInput();
            }

        } else {
            $validated['reimbursement_amount'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Fuel Entry Number
        |--------------------------------------------------------------------------
        */

        $validated['fuel_entry_no'] =
            $this->generateFuelEntryNumber();

        /*
        |--------------------------------------------------------------------------
        | Receipt Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('receipt')) {
            $validated['receipt'] =
                $request->file('receipt')->store(
                    'fuel-receipts',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Fuel Record
        |--------------------------------------------------------------------------
        */

        $fuel = Fuel::create($validated);

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->created(
            module: 'Fuel',
            subject: $fuel,
            description: "Created fuel record {$fuel->fuel_entry_no}.",
            newValues: $fuel->toArray()
        );

        return redirect()
            ->route('fuels.index')
            ->with(
                'success',
                'Fuel Record Added Successfully!'
            );
    }

    public function show($id)
    {
        $fuel = Fuel::with([
            'vehicle',
            'driver',
            'client',
            'assignment',
            'trip',
        ])->findOrFail($id);

        return view('fuels.show', compact('fuel'));
    }

    public function receipt($id)
    {
        $fuel = Fuel::findOrFail($id);

        abort_unless($fuel->receipt, 404);

        $disk = Storage::disk('public');

        abort_unless($disk->exists($fuel->receipt), 404);

        return $disk->response($fuel->receipt);
    }

    public function edit($id)
    {
        $fuel = Fuel::findOrFail($id);

        $vehicles = Vehicle::all();
        $drivers = Driver::all();
        $clients = Client::all();
        $assignments = Assignment::all();
        $trips = Trip::all();

        return view('fuels.edit', compact(
            'fuel',
            'vehicles',
            'drivers',
            'clients',
            'assignments',
            'trips'
        ));
    }

    public function update(Request $request, $id)
    {
        $fuel = Fuel::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Capture Old Values For Audit Trail
        |--------------------------------------------------------------------------
        */

        $oldValues = $fuel->getAttributes();

        $validated = $request->validate([

            'vehicle_id' => [
                'required',
                'exists:vehicles,id',
            ],

            'driver_id' => [
                'nullable',
                'exists:drivers,id',
            ],

            'client_id' => [
                'nullable',
                'exists:clients,id',
            ],

            'assignment_id' => [
                'nullable',
                'exists:assignments,id',
            ],

            'trip_id' => [
                'nullable',
                'exists:trips,id',
            ],

            'fuel_date' => [
                'required',
                'date',
                'before_or_equal:now',
            ],

            'liters' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'price_per_liter' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'total_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'odometer' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'fuel_station' => [
                'nullable',
                'string',
                'max:255',
            ],

            'paid_by' => [
                'required',
                'in:AL SHAQRA,Client,Driver,Vendor',
            ],

            'reimbursable' => [
                'required',
                'boolean',
            ],

            'reimbursement_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'receipt' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Calculate Total Cost
        |--------------------------------------------------------------------------
        */

        $liters = (float) $validated['liters'];
        $pricePerLiter = (float) ($validated['price_per_liter'] ?? 0);

        $calculatedTotal = round(
            $liters * $pricePerLiter,
            2
        );

        $validated['total_amount'] = $calculatedTotal;

        /*
        |--------------------------------------------------------------------------
        | Reimbursement Validation
        |--------------------------------------------------------------------------
        */

        if ((bool) $validated['reimbursable']) {

            if (
                !isset($validated['reimbursement_amount']) ||
                $validated['reimbursement_amount'] <= 0
            ) {
                return back()
                    ->withErrors([
                        'reimbursement_amount' =>
                            'Reimbursement amount is required when fuel is reimbursable.',
                    ])
                    ->withInput();
            }

            if ($validated['reimbursement_amount'] > $calculatedTotal) {
                return back()
                    ->withErrors([
                        'reimbursement_amount' =>
                            'Reimbursement amount cannot be greater than total fuel cost.',
                    ])
                    ->withInput();
            }

        } else {
            $validated['reimbursement_amount'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Receipt Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('receipt')) {

            if ($fuel->receipt) {
                Storage::disk('public')->delete($fuel->receipt);
            }

            $validated['receipt'] =
                $request->file('receipt')->store(
                    'fuel-receipts',
                    'public'
                );
        } else {
            unset($validated['receipt']);
        }

        /*
        |--------------------------------------------------------------------------
        | Update Fuel Record
        |--------------------------------------------------------------------------
        */

        $fuel->update($validated);

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->updated(
            module: 'Fuel',
            subject: $fuel,
            oldValues: $oldValues,
            newValues: $fuel->getAttributes(),
            description: "Updated fuel record {$fuel->fuel_entry_no}."
        );

        return redirect()
            ->route('fuels.index')
            ->with(
                'success',
                'Fuel Updated Successfully!'
            );
    }

    public function destroy($id)
    {
        $fuel = Fuel::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Capture Values Before Delete
        |--------------------------------------------------------------------------
        */

        $oldValues = $fuel->getAttributes();
        $fuelEntryNo = $fuel->fuel_entry_no;

        /*
        |--------------------------------------------------------------------------
        | Delete Receipt
        |--------------------------------------------------------------------------
        */

        if ($fuel->receipt) {
            Storage::disk('public')->delete($fuel->receipt);
        }

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->deleted(
            module: 'Fuel',
            subject: $fuel,
            oldValues: $oldValues,
            description: "Deleted fuel record {$fuelEntryNo}."
        );

        /*
        |--------------------------------------------------------------------------
        | Delete Fuel Record
        |--------------------------------------------------------------------------
        */

        $fuel->delete();

        return redirect()
            ->route('fuels.index')
            ->with(
                'success',
                'Fuel Deleted Successfully!'
            );
    }

    private function generateFuelEntryNumber(): string
    {
        $year = date('Y');

        $lastFuel = Fuel::where(
            'fuel_entry_no',
            'like',
            "AST-FUL-{$year}-%"
        )
        ->orderByDesc('id')
        ->first();

        if (!$lastFuel) {
            $number = 1;
        } else {
            $lastNumber = (int) substr(
                $lastFuel->fuel_entry_no,
                -5
            );

            $number = $lastNumber + 1;
        }

        return 'AST-FUL-' . $year . '-' . str_pad(
            $number,
            5,
            '0',
            STR_PAD_LEFT
        );
    }
}
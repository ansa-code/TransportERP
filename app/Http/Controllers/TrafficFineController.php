<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\TrafficFine;
use App\Models\Vehicle;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TrafficFineController extends Controller
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Display traffic fines.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $fines = TrafficFine::with(['vehicle', 'driver'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {

                    $q->where('fine_number', 'like', "%{$search}%")
                        ->orWhere('reason_location', 'like', "%{$search}%")

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
                        });
                });
            })
            ->latest('fine_date')
            ->paginate(10)
            ->withQueryString();

        return view('traffic_fines.index', compact(
            'fines',
            'search'
        ));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $vehicles = Vehicle::orderBy('plate_number')->get();

        $drivers = Driver::orderBy('driver_name')->get();

        return view('traffic_fines.create', compact(
            'vehicles',
            'drivers'
        ));
    }

    /**
     * Store a new traffic fine.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fine_number' => [
                'required',
                'string',
                'max:255',
                'unique:traffic_fines,fine_number',
            ],

            'vehicle_id' => [
                'nullable',
                'exists:vehicles,id'
            ],

            'driver_id' => [
                'nullable',
                'exists:drivers,id'
            ],

            'fine_date' => [
                'required',
                'date'
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0'
            ],

            'reason_location' => [
                'nullable',
                'string',
                'max:255'
            ],

            'payment_status' => [
                'required',
                'in:Unpaid,Paid,Deducted,Cancelled'
            ],

            'deduct_from_driver' => [
                'required',
                'boolean'
            ],

            'paid_date' => [
                'nullable',
                'date'
            ],

            'paid_reference' => [
                'nullable',
                'string',
                'max:255'
            ],

            'attachment' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240'
            ],
        ]);

        /*
         * At least Vehicle OR Driver is required.
         */
        if (
            empty($validated['vehicle_id']) &&
            empty($validated['driver_id'])
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'vehicle_id' => 'Vehicle or Driver is required.',
                ]);
        }

        /*
         * Paid fines require payment information.
         */
        if ($validated['payment_status'] === 'Paid') {

            if (
                empty($validated['paid_date']) ||
                empty($validated['paid_reference'])
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'paid_reference' =>
                            'Paid Date and Paid Reference are required when the fine is Paid.',
                    ]);
            }
        }

        /*
         * Prevent driver deduction when no driver is selected.
         */
        if (empty($validated['driver_id'])) {
            $validated['deduct_from_driver'] = false;
        }

        /*
         * Cancelled fine cannot be deducted.
         */
        if ($validated['payment_status'] === 'Cancelled') {
            $validated['deduct_from_driver'] = false;
        }

        /*
         * Upload attachment if provided.
         *
         * Files are stored in:
         * storage/app/public/traffic-fines
         *
         * Public URL:
         * /storage/traffic-fines/filename
         */
        if ($request->hasFile('attachment')) {

            $validated['attachment'] = $request
                ->file('attachment')
                ->store('traffic-fines', 'public');

        } else {

            $validated['attachment'] = null;
        }

        /*
         * Create traffic fine.
         *
         * Fine number is manually entered from
         * the actual traffic challan/ticket.
         */
        $fine = TrafficFine::create($validated);

        /*
         |--------------------------------------------------------------------------
         | Audit Trail
         |--------------------------------------------------------------------------
         */

        $this->activityLogService->created(
            module: 'Traffic Fine',
            subject: $fine,
            description: "Created traffic fine {$fine->fine_number}.",
            newValues: $fine->toArray()
        );

        return redirect()
            ->route('traffic-fines.index')
            ->with(
                'success',
                'Traffic Fine Added Successfully!'
            );
    }

       /**
 * Display a specific traffic fine.
 */
public function show($id)
{
    $fine = TrafficFine::with([
        'vehicle',
        'driver',
    ])->findOrFail($id);

    return view(
        'traffic_fines.show',
        compact('fine')
    );
}

/**
 * Display traffic fine attachment.
 */
public function attachment($id)
{
    $fine = TrafficFine::findOrFail($id);

    abort_unless($fine->attachment, 404);

    $disk = Storage::disk('public');

    abort_unless($disk->exists($fine->attachment), 404);

    return $disk->response($fine->attachment);
}

/**
 * Show edit form.
 */
public function edit($id)
{
    $fine = TrafficFine::findOrFail($id);

    $vehicles = Vehicle::orderBy('plate_number')->get();

    $drivers = Driver::orderBy('driver_name')->get();

    return view(
        'traffic_fines.edit',
        compact(
            'fine',
            'vehicles',
            'drivers'
        )
    );
}

    /**
     * Update an existing traffic fine.
     */
    public function update(Request $request, $id)
    {
        $fine = TrafficFine::findOrFail($id);

        /*
         * Capture old values before update.
         */
        $oldValues = $fine->getAttributes();

        $validated = $request->validate([
            'fine_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('traffic_fines', 'fine_number')
                    ->ignore($fine->id),
            ],

            'vehicle_id' => [
                'nullable',
                'exists:vehicles,id'
            ],

            'driver_id' => [
                'nullable',
                'exists:drivers,id'
            ],

            'fine_date' => [
                'required',
                'date'
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0'
            ],

            'reason_location' => [
                'nullable',
                'string',
                'max:255'
            ],

            'payment_status' => [
                'required',
                'in:Unpaid,Paid,Deducted,Cancelled'
            ],

            'deduct_from_driver' => [
                'required',
                'boolean'
            ],

            'paid_date' => [
                'nullable',
                'date'
            ],

            'paid_reference' => [
                'nullable',
                'string',
                'max:255'
            ],

            'attachment' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240'
            ],
        ]);

        /*
         * At least Vehicle OR Driver is required.
         */
        if (
            empty($validated['vehicle_id']) &&
            empty($validated['driver_id'])
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'vehicle_id' => 'Vehicle or Driver is required.',
                ]);
        }

        /*
         * Paid fines require payment information.
         */
        if ($validated['payment_status'] === 'Paid') {

            if (
                empty($validated['paid_date']) ||
                empty($validated['paid_reference'])
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'paid_reference' =>
                            'Paid Date and Paid Reference are required when the fine is Paid.',
                    ]);
            }
        }

        /*
         * Fine cannot be deducted without a driver.
         */
        if (empty($validated['driver_id'])) {
            $validated['deduct_from_driver'] = false;
        }

        /*
         * Cancelled fine cannot be deducted.
         */
        if ($validated['payment_status'] === 'Cancelled') {
            $validated['deduct_from_driver'] = false;
        }

        /*
         * Once a fine has already been deducted,
         * keep it as Deducted so it cannot be
         * switched back accidentally.
         */
        if ($fine->payment_status === 'Deducted') {
            $validated['payment_status'] = 'Deducted';
            $validated['deduct_from_driver'] = true;
        }

        /*
         * Handle attachment replacement.
         *
         * If a new attachment is uploaded:
         * 1. Delete old file.
         * 2. Store new file.
         *
         * If no new attachment is uploaded:
         * old attachment remains unchanged.
         */
        if ($request->hasFile('attachment')) {

            if (
                !empty($fine->attachment) &&
                Storage::disk('public')->exists($fine->attachment)
            ) {
                Storage::disk('public')->delete($fine->attachment);
            }

            $validated['attachment'] = $request
                ->file('attachment')
                ->store('traffic-fines', 'public');

        } else {

            /*
             * Keep existing attachment.
             */
            $validated['attachment'] = $fine->attachment;
        }

        $fine->update($validated);

        /*
         |--------------------------------------------------------------------------
         | Audit Trail
         |--------------------------------------------------------------------------
         */

        $this->activityLogService->updated(
            module: 'Traffic Fine',
            subject: $fine,
            oldValues: $oldValues,
            newValues: $fine->getAttributes(),
            description: "Updated traffic fine {$fine->fine_number}."
        );

        return redirect()
            ->route('traffic-fines.index')
            ->with(
                'success',
                'Traffic Fine Updated Successfully!'
            );
    }

    /**
     * Delete a traffic fine.
     */
    public function destroy($id)
    {
        $fine = TrafficFine::findOrFail($id);

        /*
         * A deducted fine is part of payroll/audit history
         * and should not be deleted.
         */
        if ($fine->payment_status === 'Deducted') {

            return redirect()
                ->route('traffic-fines.index')
                ->withErrors([
                    'delete' =>
                        'A deducted traffic fine cannot be deleted because it is part of payroll/audit history.',
                ]);
        }

        /*
         * Capture values before delete.
         */
        $oldValues = $fine->getAttributes();
        $fineNumber = $fine->fine_number;

        /*
         * Delete attachment from storage.
         */
        if (
            !empty($fine->attachment) &&
            Storage::disk('public')->exists($fine->attachment)
        ) {
            Storage::disk('public')->delete($fine->attachment);
        }

        /*
         |--------------------------------------------------------------------------
         | Audit Trail
         |--------------------------------------------------------------------------
         */

        $this->activityLogService->deleted(
            module: 'Traffic Fine',
            subject: $fine,
            oldValues: $oldValues,
            description: "Deleted traffic fine {$fineNumber}."
        );

        $fine->delete();

        return redirect()
            ->route('traffic-fines.index')
            ->with(
                'success',
                'Traffic Fine Deleted Successfully!'
            );
    }
}
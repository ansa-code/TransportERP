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

        return view('traffic_fines.index', compact('fines', 'search'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $vehicles = Vehicle::orderBy('plate_number')->get();
        $drivers = Driver::orderBy('driver_name')->get();

        return view('traffic_fines.create', compact('vehicles', 'drivers'));
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
                'max:50',
                'regex:/^[A-Za-z0-9\/.\- ]+$/',
                'unique:traffic_fines,fine_number',
            ],

            'vehicle_id' => ['nullable', 'exists:vehicles,id'],
            'driver_id' => ['nullable', 'exists:drivers,id'],

            'fine_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
                'max:9999999999.99',
                'regex:/^\d{1,10}(\.\d{1,2})?$/',
            ],

            'reason_location' => ['nullable', 'string', 'max:100'],

            'payment_status' => [
                'required',
                Rule::in(['Unpaid', 'Paid', 'Deducted', 'Cancelled']),
            ],

            'deduct_from_driver' => ['required', 'boolean'],

            'paid_date' => [
                'exclude_unless:payment_status,Paid',
                'required',
                'date',
                'before_or_equal:today',
                'after_or_equal:fine_date',
            ],

            'paid_reference' => [
                'exclude_unless:payment_status,Paid',
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9\/._#\- ]+$/',
            ],

            'attachment' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240',
            ],
        ], [
            'fine_number.required' => 'Fine number is required.',
            'fine_number.max' => 'Fine number cannot exceed 50 characters.',
            'fine_number.regex' => 'Fine number may contain only letters, numbers, spaces, dots, hyphens and slashes.',
            'fine_number.unique' => 'This fine number already exists.',

            'fine_date.required' => 'Fine Date is required.',
            'fine_date.before_or_equal' => 'Fine Date cannot be in the future.',

            'amount.required' => 'Fine amount is required.',
            'amount.numeric' => 'Fine amount must be a valid number.',
            'amount.gt' => 'Fine amount must be greater than zero.',
            'amount.max' => 'Fine amount exceeds the maximum allowed value.',
            'amount.regex' => 'Fine amount may have up to 10 digits before the decimal and 2 after it.',

            'reason_location.max' => 'Reason / Location cannot exceed 100 characters.',

            'paid_date.required' => 'Paid Date is required when Payment Status is Paid.',
            'paid_date.date' => 'Please enter a valid Paid Date.',
            'paid_date.before_or_equal' => 'Paid Date cannot be in the future.',
            'paid_date.after_or_equal' => 'Paid Date cannot be earlier than Fine Date.',

            'paid_reference.required' => 'Paid Reference is required when Payment Status is Paid.',
            'paid_reference.max' => 'Paid Reference cannot exceed 50 characters.',
            'paid_reference.regex' => 'Paid Reference contains invalid characters.',

            'attachment.mimes' => 'Attachment must be a JPG, JPEG, PNG or PDF file.',
            'attachment.max' => 'Attachment cannot exceed 10 MB.',
        ]);

        /*
         * At least Vehicle OR Driver is required.
         */
        if (empty($validated['vehicle_id']) && empty($validated['driver_id'])) {
            return back()->withInput()->withErrors([
                'vehicle_id' => 'Please select at least one Vehicle or Driver.',
            ]);
        }

        /*
         * Validate Payment Status against Driver Deduction.
         */
        $status = $validated['payment_status'];
        $deduct = (bool) $validated['deduct_from_driver'];

        if ($status === 'Unpaid' && $deduct) {
            return back()->withInput()->withErrors([
                'deduct_from_driver' => 'An Unpaid fine cannot be marked for driver deduction.',
            ]);
        }

        if ($status === 'Deducted' && !$deduct) {
            return back()->withInput()->withErrors([
                'deduct_from_driver' => 'Deduct from Driver must be Yes when Payment Status is Deducted.',
            ]);
        }

        if ($status === 'Cancelled' && $deduct) {
            return back()->withInput()->withErrors([
                'deduct_from_driver' => 'A Cancelled fine cannot be deducted from the driver.',
            ]);
        }

        /*
         * Prevent deduction when no driver is selected.
         */
        if (empty($validated['driver_id'])) {
            $validated['deduct_from_driver'] = false;
        }

        /*
         * Upload attachment if provided.
         */
        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request
                ->file('attachment')
                ->store('traffic-fines', 'public');
        } else {
            $validated['attachment'] = null;
        }

        $fine = TrafficFine::create($validated);

        /*
         * Audit Trail.
         */
        $this->activityLogService->created(
            module: 'Traffic Fine',
            subject: $fine,
            description: "Created traffic fine {$fine->fine_number}.",
            newValues: $fine->toArray()
        );

        return redirect()
            ->route('traffic-fines.index')
            ->with('success', 'Traffic Fine Added Successfully!');
    }

    /**
     * Display a specific traffic fine.
     */
    public function show($id)
    {
        $fine = TrafficFine::with(['vehicle', 'driver'])->findOrFail($id);

        return view('traffic_fines.show', compact('fine'));
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
            compact('fine', 'vehicles', 'drivers')
        );
    }

    /**
     * Update an existing traffic fine.
     */
    public function update(Request $request, $id)
    {
        $fine = TrafficFine::findOrFail($id);

        $oldValues = $fine->getAttributes();

        $validated = $request->validate([
            'fine_number' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9\/.\- ]+$/',
                Rule::unique('traffic_fines', 'fine_number')
                    ->ignore($fine->id),
            ],

            'vehicle_id' => ['nullable', 'exists:vehicles,id'],
            'driver_id' => ['nullable', 'exists:drivers,id'],

            'fine_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
                'max:9999999999.99',
                'regex:/^\d{1,10}(\.\d{1,2})?$/',
            ],

            'reason_location' => ['nullable', 'string', 'max:100'],

            'payment_status' => [
                'required',
                Rule::in(['Unpaid', 'Paid', 'Deducted', 'Cancelled']),
            ],

            'deduct_from_driver' => ['required', 'boolean'],

            'paid_date' => [
                'exclude_unless:payment_status,Paid',
                'required',
                'date',
                'before_or_equal:today',
                'after_or_equal:fine_date',
            ],

            'paid_reference' => [
                'exclude_unless:payment_status,Paid',
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9\/._#\- ]+$/',
            ],

            'attachment' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240',
            ],
        ], [
            'fine_number.required' => 'Fine number is required.',
            'fine_number.max' => 'Fine number cannot exceed 50 characters.',
            'fine_number.regex' => 'Fine number may contain only letters, numbers, spaces, dots, hyphens and slashes.',
            'fine_number.unique' => 'This fine number already exists.',

            'fine_date.required' => 'Fine Date is required.',
            'fine_date.before_or_equal' => 'Fine Date cannot be in the future.',

            'amount.required' => 'Fine amount is required.',
            'amount.numeric' => 'Fine amount must be a valid number.',
            'amount.gt' => 'Fine amount must be greater than zero.',
            'amount.max' => 'Fine amount exceeds the maximum allowed value.',
            'amount.regex' => 'Fine amount may have up to 10 digits before the decimal and 2 after it.',

            'reason_location.max' => 'Reason / Location cannot exceed 100 characters.',

            'paid_date.required' => 'Paid Date is required when Payment Status is Paid.',
            'paid_date.date' => 'Please enter a valid Paid Date.',
            'paid_date.before_or_equal' => 'Paid Date cannot be in the future.',
            'paid_date.after_or_equal' => 'Paid Date cannot be earlier than Fine Date.',

            'paid_reference.required' => 'Paid Reference is required when Payment Status is Paid.',
            'paid_reference.max' => 'Paid Reference cannot exceed 50 characters.',
            'paid_reference.regex' => 'Paid Reference contains invalid characters.',

            'attachment.mimes' => 'Attachment must be a JPG, JPEG, PNG or PDF file.',
            'attachment.max' => 'Attachment cannot exceed 10 MB.',
        ]);

        /*
         * At least Vehicle OR Driver is required.
         */
        if (empty($validated['vehicle_id']) && empty($validated['driver_id'])) {
            return back()->withInput()->withErrors([
                'vehicle_id' => 'Please select at least one Vehicle or Driver.',
            ]);
        }

        /*
         * Validate Payment Status against Driver Deduction.
         */
        $status = $validated['payment_status'];
        $deduct = (bool) $validated['deduct_from_driver'];

        if ($status === 'Unpaid' && $deduct) {
            return back()->withInput()->withErrors([
                'deduct_from_driver' => 'An Unpaid fine cannot be marked for driver deduction.',
            ]);
        }

        if ($status === 'Deducted' && !$deduct) {
            return back()->withInput()->withErrors([
                'deduct_from_driver' => 'Deduct from Driver must be Yes when Payment Status is Deducted.',
            ]);
        }

        if ($status === 'Cancelled' && $deduct) {
            return back()->withInput()->withErrors([
                'deduct_from_driver' => 'A Cancelled fine cannot be deducted from the driver.',
            ]);
        }

        /*
         * Preserve the existing rule for already-deducted fines.
         */
        if ($fine->payment_status === 'Deducted') {
            $validated['payment_status'] = 'Deducted';
            $validated['deduct_from_driver'] = true;
        }

        /*
         * Prevent deduction when no driver is selected.
         */
        if (empty($validated['driver_id'])) {
            $validated['deduct_from_driver'] = false;
        }

        /*
         * Replace attachment only when a new one is uploaded.
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
            $validated['attachment'] = $fine->attachment;
        }

        $fine->update($validated);

        /*
         * Audit Trail.
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
            ->with('success', 'Traffic Fine Updated Successfully!');
    }

    /**
     * Delete a traffic fine.
     */
    public function destroy($id)
    {
        $fine = TrafficFine::findOrFail($id);

        /*
         * A deducted fine is part of payroll/audit history.
         */
        if ($fine->payment_status === 'Deducted') {
            return redirect()
                ->route('traffic-fines.index')
                ->withErrors([
                    'delete' => 'A deducted traffic fine cannot be deleted because it is part of payroll/audit history.',
                ]);
        }

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
         * Audit Trail.
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
            ->with('success', 'Traffic Fine Deleted Successfully!');
    }
}
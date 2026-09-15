<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Vehicle;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /*
    |--------------------------------------------------------------------------
    | Vendor Listing
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = $request->search;

        $vendorQuery = Vendor::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {

                    $q->where('vendor_name', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('contact', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('service_type', 'like', "%{$search}%")
                        ->orWhere(
                            'supplied_vehicle_plates',
                            'like',
                            "%{$search}%"
                        );

                });
            });

        $totalVendors = (clone $vendorQuery)->count();

        $activeVendors = (clone $vendorQuery)
            ->where('status', 'Active')
            ->count();

        $inactiveVendors = (clone $vendorQuery)
            ->where('status', 'Inactive')
            ->count();

        $archivedVendors = (clone $vendorQuery)
            ->where('status', 'Archived')
            ->count();

        $vendors = (clone $vendorQuery)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'vendors.index',
            compact(
                'vendors',
                'search',
                'totalVendors',
                'activeVendors',
                'inactiveVendors',
                'archivedVendors'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create Vendor Form
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $vehicles = Vehicle::orderBy('plate_number')->get();

        return view(
            'vendors.create',
            compact('vehicles')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store Vendor
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'vendor_name' => [
                'required',
                'string',
                'max:255',
            ],

            'company_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'contact' => [
                'nullable',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'service_type' => [
                'required',
                'array',
                'min:1',
            ],

            'service_type.*' => [
                'required',
                'string',
                'max:100',
            ],

            'supplied_vehicle_plates' => [
                'nullable',
                'array',
            ],

            'supplied_vehicle_plates.*' => [
                'exists:vehicles,plate_number',
            ],

            'rate_per_day' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'rate_per_month' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'rate_per_trip' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'custom_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'custom_rate_label' => [
                'nullable',
                'string',
                'max:255',
            ],

            'payment_terms' => [
                'nullable',
                'string',
                'max:255',
            ],

            'payment_terms_days' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'tax_registration_data' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Active,Inactive,Archived',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Store Multiple Values
        |--------------------------------------------------------------------------
        */

        $validated['service_type'] = implode(
            ', ',
            $validated['service_type']
        );

        $validated['supplied_vehicle_plates'] =
            !empty($validated['supplied_vehicle_plates'])
                ? implode(
                    ', ',
                    $validated['supplied_vehicle_plates']
                )
                : null;

        $vendor = Vendor::create($validated);

        $this->activityLogService->created(
            module: 'Vendors',
            subject: $vendor,
            description:
                "Created vendor record: {$vendor->vendor_name}."
        );

        return redirect()
            ->route('vendors.index')
            ->with(
                'success',
                'Vendor Added Successfully!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Show Vendor
    |--------------------------------------------------------------------------
    */

    public function show(string $id)
    {
        $vendor = Vendor::findOrFail($id);

        return view(
            'vendors.show',
            compact('vendor')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Vendor Form
    |--------------------------------------------------------------------------
    */

    public function edit(string $id)
    {
        $vendor = Vendor::findOrFail($id);

        $vehicles = Vehicle::orderBy('plate_number')->get();

        return view(
            'vendors.edit',
            compact(
                'vendor',
                'vehicles'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Vendor
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        string $id
    ) {
        $vendor = Vendor::findOrFail($id);

        $validated = $request->validate([

            'vendor_name' => [
                'required',
                'string',
                'max:255',
            ],

            'company_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'contact' => [
                'nullable',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'service_type' => [
                'required',
                'array',
                'min:1',
            ],

            'service_type.*' => [
                'required',
                'string',
                'max:100',
            ],

            'supplied_vehicle_plates' => [
                'nullable',
                'array',
            ],

            'supplied_vehicle_plates.*' => [
                'exists:vehicles,plate_number',
            ],

            'rate_per_day' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'rate_per_month' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'rate_per_trip' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'custom_rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'custom_rate_label' => [
                'nullable',
                'string',
                'max:255',
            ],

            'payment_terms' => [
                'nullable',
                'string',
                'max:255',
            ],

            'payment_terms_days' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'tax_registration_data' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:Active,Inactive,Archived',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Store Multiple Values
        |--------------------------------------------------------------------------
        */

        $validated['service_type'] = implode(
            ', ',
            $validated['service_type']
        );

        $validated['supplied_vehicle_plates'] =
            !empty($validated['supplied_vehicle_plates'])
                ? implode(
                    ', ',
                    $validated['supplied_vehicle_plates']
                )
                : null;

        $oldValues = $vendor->getAttributes();

        $vendor->update($validated);

        $this->activityLogService->updated(
            module: 'Vendors',
            subject: $vendor,
            oldValues: $oldValues,
            newValues: $vendor->getAttributes(),
            description:
                "Updated vendor record: {$vendor->vendor_name}."
        );

        return redirect()
            ->route('vendors.index')
            ->with(
                'success',
                'Vendor Updated Successfully!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Vendor
    |--------------------------------------------------------------------------
    */

    public function destroy(string $id)
    {
        $vendor = Vendor::findOrFail($id);

        $oldValues = $vendor->getAttributes();
        $vendorName = $vendor->vendor_name;

        $this->activityLogService->deleted(
            module: 'Vendors',
            subject: $vendor,
            oldValues: $oldValues,
            description:
                "Deleted vendor record: {$vendorName}."
        );

        $vendor->delete();

        return redirect()
            ->route('vendors.index')
            ->with(
                'success',
                'Vendor Deleted Successfully!'
            );
    }
}
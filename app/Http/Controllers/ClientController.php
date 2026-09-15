<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Client;
use App\Models\Invoice;
use App\Services\ActivityLogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /*
    |--------------------------------------------------------------------------
    | Client List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = $request->search;

        /*
        |--------------------------------------------------------------------------
        | Clients
        |--------------------------------------------------------------------------
        */
         
        $clients = Client::query()
    ->when($search, function ($query) use ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('client_name', 'like', "%{$search}%")
                ->orWhere('company_name', 'like', "%{$search}%")
                ->orWhere('client_code', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('trn', 'like', "%{$search}%")
                ->orWhere('trade_licence', 'like', "%{$search}%")
                ->orWhere('contact_person', 'like', "%{$search}%");
        });
    })
    ->when($request->billing_type, function ($query, $billingType) {
        $query->where('billing_type', $billingType);
    })
    ->when($request->city, function ($query, $city) {
        $query->where('city', $city);
    })
    ->when($request->status, function ($query, $status) {
        $query->where('status', $status);
    })
    ->latest()
    ->get();
        

        /*
        |--------------------------------------------------------------------------
        | Current Date
        |--------------------------------------------------------------------------
        */

        $today = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | KPI - Active Clients
        |--------------------------------------------------------------------------
        */

        $activeClientsCount = Client::where(
            'status',
            'Active'
        )->count();

        /*
        |--------------------------------------------------------------------------
        | KPI - Trucks Assigned
        |--------------------------------------------------------------------------
        |
        | Count unique vehicles that currently have an assignment.
        |
        | Current assignment:
        | start_date <= today
        | and end_date is null OR end_date >= today
        |
        |--------------------------------------------------------------------------
        */

        $trucksAssignedCount = Assignment::query()
            ->whereNotNull('client_id')
            ->whereNotNull('vehicle_id')
            ->whereDate(
                'start_date',
                '<=',
                $today
            )
            ->where(function ($query) use ($today) {
                $query->whereNull('end_date')
                    ->orWhereDate(
                        'end_date',
                        '>=',
                        $today
                    );
            })
            ->distinct()
            ->count('vehicle_id');

        /*
        |--------------------------------------------------------------------------
        | KPI - Receivables
        |--------------------------------------------------------------------------
        |
        | Invoice balance already represents outstanding amount.
        |
        |--------------------------------------------------------------------------
        */

        $receivables = Invoice::query()
            ->where('balance', '>', 0)
            ->sum('balance');

        /*
        |--------------------------------------------------------------------------
        | KPI - Renewals Due
        |--------------------------------------------------------------------------
        |
        | Active contracts ending within the next 30 days.
        |
        |--------------------------------------------------------------------------
        */

        $renewalsDueCount = Client::query()
            ->where('status', 'Active')
            ->whereNotNull('contract_end')
            ->whereDate(
                'contract_end',
                '>=',
                $today
            )
            ->whereDate(
                'contract_end',
                '<=',
                $today->copy()->addDays(30)
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Client Table - Current Truck Count
        |--------------------------------------------------------------------------
        */

        $clientVehicleCounts = Assignment::query()
            ->whereNotNull('client_id')
            ->whereNotNull('vehicle_id')
            ->whereDate(
                'start_date',
                '<=',
                $today
            )
            ->where(function ($query) use ($today) {
                $query->whereNull('end_date')
                    ->orWhereDate(
                        'end_date',
                        '>=',
                        $today
                    );
            })
            ->select('client_id')
            ->selectRaw(
                'COUNT(DISTINCT vehicle_id) as trucks_count'
            )
            ->groupBy('client_id')
            ->pluck(
                'trucks_count',
                'client_id'
            );

        /*
        |--------------------------------------------------------------------------
        | Attach Truck Count To Each Client
        |--------------------------------------------------------------------------
        */

        foreach ($clients as $client) {
            $client->trucks_count = (int) (
                $clientVehicleCounts[$client->id] ?? 0
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'clients.index',
            compact(
                'clients',
                'search',
                'activeClientsCount',
                'trucksAssignedCount',
                'receivables',
                'renewalsDueCount'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create Client
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('clients.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Store Client
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'client_code' => [
                'required',
                'string',
                'max:255',
                'unique:clients,client_code',
            ],

            'client_name' => [
                'required',
                'string',
                'max:255',
            ],

            'company_name' => [
                'required',
                'string',
                'max:255',
            ],

            'contact_person' => [
                'nullable',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:clients,email',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'city' => [
                'nullable',
                'string',
                'max:255',
            ],

            'country' => [
                'nullable',
                'string',
                'max:255',
            ],

            'trade_licence' => [
                'nullable',
                'string',
                'max:255',
            ],

            'trade_licence_expiry' => [
                'nullable',
                'date',
            ],

            'trn' => [
                'nullable',
                'string',
                'max:255',
            ],

            'contract_start' => [
                'nullable',
                'date',
            ],

            'contract_end' => [
                'nullable',
                'date',
                'after_or_equal:contract_start',
            ],

            'billing_type' => [
                'required',
                Rule::in([
                    'Per Trip',
                    'Weekly',
                    'Monthly',
                    'Fixed Rent',
                    'Usage',
                    'Mixed',
                    'Custom',
                ]),
            ],

            'vat_applicable' => [
                'required',
                'boolean',
            ],

            'payment_terms' => [
                'nullable',
                'string',
                'max:255',
            ],

            'credit_days' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'credit_limit' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'fuel_reimbursement_rule' => [
                'nullable',
                Rule::in([
                    'None',
                    'Actual Cost',
                    'Markup',
                    'Fixed',
                    'Custom',
                ]),
            ],

            'status' => [
                'required',
                Rule::in([
                    'Active',
                    'Inactive',
                    'Archived',
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        $client = Client::create($validated);

        $this->activityLogService->created(
            module: 'Clients',
            subject: $client,
            description: "Created client record: {$client->client_name}."
        );

        return redirect()
            ->route('clients.index')
            ->with(
                'success',
                'Client Added Successfully!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Show Client
    |--------------------------------------------------------------------------
    */

    public function show(string $id)
    {
        $client = Client::findOrFail($id);

        return view(
            'clients.show',
            compact('client')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Client
    |--------------------------------------------------------------------------
    */

    public function edit(string $id)
    {
        $client = Client::findOrFail($id);

        return view(
            'clients.edit',
            compact('client')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Client
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, string $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([

            'client_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('clients', 'client_code')
                    ->ignore($client->id),
            ],

            'client_name' => [
                'required',
                'string',
                'max:255',
            ],

            'company_name' => [
                'required',
                'string',
                'max:255',
            ],

            'contact_person' => [
                'nullable',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('clients', 'email')
                    ->ignore($client->id),
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'city' => [
                'nullable',
                'string',
                'max:255',
            ],

            'country' => [
                'nullable',
                'string',
                'max:255',
            ],

            'trade_licence' => [
                'nullable',
                'string',
                'max:255',
            ],

            'trade_licence_expiry' => [
                'nullable',
                'date',
            ],

            'trn' => [
                'nullable',
                'string',
                'max:255',
            ],

            'contract_start' => [
                'nullable',
                'date',
            ],

            'contract_end' => [
                'nullable',
                'date',
                'after_or_equal:contract_start',
            ],

            'billing_type' => [
                'required',
                Rule::in([
                    'Per Trip',
                    'Weekly',
                    'Monthly',
                    'Fixed Rent',
                    'Usage',
                    'Mixed',
                    'Custom',
                ]),
            ],

            'vat_applicable' => [
                'required',
                'boolean',
            ],

            'payment_terms' => [
                'nullable',
                'string',
                'max:255',
            ],

            'credit_days' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'credit_limit' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'fuel_reimbursement_rule' => [
                'nullable',
                Rule::in([
                    'None',
                    'Actual Cost',
                    'Markup',
                    'Fixed',
                    'Custom',
                ]),
            ],

            'status' => [
                'required',
                Rule::in([
                    'Active',
                    'Inactive',
                    'Archived',
                ]),
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        $oldValues = $client->getAttributes();

        $client->update($validated);

        $this->activityLogService->updated(
            module: 'Clients',
            subject: $client,
            oldValues: $oldValues,
            newValues: $client->getAttributes(),
            description: "Updated client record: {$client->client_name}."
        );

        return redirect()
            ->route('clients.index')
            ->with(
                'success',
                'Client Updated Successfully!'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Client
    |--------------------------------------------------------------------------
    */

    public function destroy(string $id)
    {
        $client = Client::findOrFail($id);

        $oldValues = $client->getAttributes();
        $clientName = $client->client_name;

        $this->activityLogService->deleted(
            module: 'Clients',
            subject: $client,
            oldValues: $oldValues,
            description: "Deleted client record: {$clientName}."
        );

        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with(
                'success',
                'Client Deleted Successfully!'
            );
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Assignment;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $invoices = Invoice::with(['client', 'assignment'])
            ->when($search, function ($query) use ($search) {

                $query->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('client_name', 'like', "%{$search}%");
                    });

            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('invoices.index', compact(
            'invoices',
            'search'
        ));
    }

    public function create()
    {
        $clients = Client::all();
        $assignments = Assignment::with(['client'])->get();

        return view('invoices.create', compact(
            'clients',
            'assignments'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|unique:invoices,invoice_number',
            'client_id' => 'required|exists:clients,id',
            'assignment_id' => 'nullable|exists:assignments,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required',
        ]);

        Invoice::create($request->all());

        return redirect('/invoices')
            ->with('success', 'Invoice Added Successfully!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $invoice = Invoice::findOrFail($id);

        $clients = Client::all();
        $assignments = Assignment::with(['client'])->get();

        return view('invoices.edit', compact(
            'invoice',
            'clients',
            'assignments'
        ));
    }

    public function update(Request $request, string $id)
    {
        $invoice = Invoice::findOrFail($id);

        $request->validate([
            'invoice_number' => 'required|unique:invoices,invoice_number,' . $invoice->id,
            'client_id' => 'required|exists:clients,id',
            'assignment_id' => 'nullable|exists:assignments,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required',
        ]);

        $invoice->update($request->all());

        return redirect('/invoices')
            ->with('success', 'Invoice Updated Successfully!');
    }

    public function destroy(string $id)
    {
        $invoice = Invoice::findOrFail($id);

        $invoice->delete();

        return redirect('/invoices')
            ->with('success', 'Invoice Deleted Successfully!');
    }
}
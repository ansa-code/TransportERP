<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Driver;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $payrolls = Payroll::with('driver')
            ->when($search, function ($query) use ($search) {

                $query->where('salary_month', 'like', "%{$search}%")
                    ->orWhereHas('driver', function ($q) use ($search) {
                        $q->where('driver_name', 'like', "%{$search}%");
                    });

            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('payrolls.index', compact(
            'payrolls',
            'search'
        ));
    }

    public function create()
    {
        $drivers = Driver::all();

        return view('payrolls.create', compact('drivers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'salary_month' => 'required',
            'basic_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'deduction' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'status' => 'required',
        ]);

        $allowance = $request->allowance ?? 0;
        $deduction = $request->deduction ?? 0;

        $netSalary = $request->basic_salary + $allowance - $deduction;

        Payroll::create([
            'driver_id' => $request->driver_id,
            'salary_month' => $request->salary_month,
            'basic_salary' => $request->basic_salary,
            'allowance' => $allowance,
            'deduction' => $deduction,
            'net_salary' => $netSalary,
            'payment_date' => $request->payment_date,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect('/payrolls')
            ->with('success', 'Payroll Added Successfully!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $payroll = Payroll::findOrFail($id);

        $drivers = Driver::all();

        return view('payrolls.edit', compact(
            'payroll',
            'drivers'
        ));
    }

    public function update(Request $request, string $id)
    {
        $payroll = Payroll::findOrFail($id);

        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'salary_month' => 'required',
            'basic_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'deduction' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'status' => 'required',
        ]);

        $allowance = $request->allowance ?? 0;
        $deduction = $request->deduction ?? 0;

        $netSalary = $request->basic_salary + $allowance - $deduction;

        $payroll->update([
            'driver_id' => $request->driver_id,
            'salary_month' => $request->salary_month,
            'basic_salary' => $request->basic_salary,
            'allowance' => $allowance,
            'deduction' => $deduction,
            'net_salary' => $netSalary,
            'payment_date' => $request->payment_date,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect('/payrolls')
            ->with('success', 'Payroll Updated Successfully!');
    }

    public function destroy(string $id)
    {
        $payroll = Payroll::findOrFail($id);

        $payroll->delete();

        return redirect('/payrolls')
            ->with('success', 'Payroll Deleted Successfully!');
    }
}
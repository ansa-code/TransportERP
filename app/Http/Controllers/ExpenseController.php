<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $expenses = Expense::whereHas('vehicle', function ($q) use ($search) {

            $q->where('vehicle_number', 'like', "%$search%");

        })->paginate(5);

        return view('expenses.index', compact(
            'expenses',
            'search'
        ));
    }

    public function create()
    {
        $vehicles = Vehicle::all();

        return view('expenses.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        Expense::create($request->all());

        return redirect('/expenses')
            ->with('success','Expense Added Successfully!');
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);

        $vehicles = Vehicle::all();

        return view('expenses.edit', compact(
            'expense',
            'vehicles'
        ));
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $expense->update($request->all());

        return redirect('/expenses')
            ->with('success','Expense Updated Successfully!');
    }

    public function destroy($id)
    {
        Expense::findOrFail($id)->delete();

        return redirect('/expenses')
            ->with('success','Expense Deleted Successfully!');
    }
}
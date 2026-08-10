<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $search = $request->search;

    $vendors = Vendor::where('vendor_name','like',"%$search%")
        ->orWhere('company_name','like',"%$search%")
        ->orWhere('phone','like',"%$search%")
        ->paginate(5);

    return view('vendors.index', compact('vendors','search'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    return view('vendors.create');
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    Vendor::create([

        'vendor_name' => $request->vendor_name,
        'company_name' => $request->company_name,
        'phone' => $request->phone,
        'email' => $request->email,
        'address' => $request->address,
        'service_type' => $request->service_type,
        'status' => $request->status,
        'notes' => $request->notes,

    ]);

    return redirect('/vendors')
        ->with('success', 'Vendor Added Successfully!');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
     public function edit($id)
{
    $vendor = Vendor::findOrFail($id);

    return view('vendors.edit', compact('vendor'));
}
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $vendor = Vendor::findOrFail($id);

    $vendor->update($request->all());

    return redirect('/vendors')
        ->with('success','Vendor Updated Successfully!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $vendor = Vendor::findOrFail($id);

    $vendor->delete();

    return redirect('/vendors')
        ->with('success','Vendor Deleted Successfully!');
}
}

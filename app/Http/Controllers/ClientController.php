<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
   public function index(Request $request)
{
    $search = $request->search;

    $clients = Client::where('client_name', 'like', "%$search%")
        ->orWhere('company_name', 'like', "%$search%")
        ->orWhere('phone', 'like', "%$search%")
        ->paginate(5);

    return view('clients.index', compact('clients', 'search'));
}

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        Client::create($request->all());

        return redirect('/clients')
            ->with('success','Client Added Successfully!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
{
    $client = Client::findOrFail($id);

    return view('clients.edit', compact('client'));
}

    public function update(Request $request, string $id)
{
    $client = Client::findOrFail($id);

    $client->update($request->all());

    return redirect('/clients')
        ->with('success', 'Client Updated Successfully!');
}
    public function destroy(string $id)
{
    $client = Client::findOrFail($id);

    $client->delete();

    return redirect('/clients')
        ->with('success', 'Client Deleted Successfully!');
}
}

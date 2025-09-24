<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Includes search functionality
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('full_name', 'like', "%{$searchTerm}%")
            ->orWhere('phone', 'like', "%{$searchTerm}%");
        }

        $clients = $query->latest()->paginate(10);

        return view('clients.index', compact('clients'));
    }
    //create 
    public function create()
    {
        return view('clients.create');
    }


    
    public function store(Request $request)
{
    $request->validate([
        'full_name' => 'required|string|max:255',
        'phone' => 'nullable|string|unique:clients,phone',
        'address' => 'nullable|string',
        'gender'=>'nullable|string',
        'notes' => 'nullable|string',
    ]);

    Client::create($request->only(['full_name', 'phone', 'address','gender', 'notes']));

    return redirect()->route('clients.index')->with('success', 'Client added successfully.');
}

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|unique:clients,phone,' . $client->id,
            'address' => 'nullable|string',
            'gender'=>'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $client->update($request->all());

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        // Check if client has any bookings
        if ($client->bookings()->exists()) {
            return redirect()->route('clients.index')
                ->with('error', 'Cannot delete client. This client has existing bookings. Please remove all bookings first.');
        }
        
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
    
    // For the client history page
    public function history(Client $client)
    {
        $client->load('bookings.serviceEmployees.service', 'bookings.serviceEmployees.employee');
        return view('clients.history', compact('client'));
    }
}
<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingServiceEmployee;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create()
    {
        $clients = Client::orderBy('full_name')->get();
        $services = Service::all();
        $employees = Employee::with('services')->get(); // eager load pivot
        $counter = 1; // default first row

        return view('bookings.create', compact('clients','services','employees','counter'));
    }

    public function edit(Booking $booking)
    {
        try {
            $clients = Client::orderBy('full_name')->get();
            $services = Service::all();
            $employees = Employee::with('services')->get();
            
            // Load the booking with its service employees and their relationships
            $booking->load(['serviceEmployees.service', 'serviceEmployees.employee']);
            
            // Prepare existing services data
            $existingServices = old('services', $booking->serviceEmployees->map(function($se) {
                return ['service_id' => $se->service_id, 'employee_id' => $se->employee_id];
            })->toArray());
            
            if (empty($existingServices)) {
                $existingServices = [['service_id' => '', 'employee_id' => '']];
            }
            
            // Ensure we have at least one service row for editing
            $counter = max(1, $booking->serviceEmployees->count());

            return view('bookings.edit', compact('booking','clients','services','employees','counter','existingServices'));
        } catch (\Exception $e) {
            \Log::error('Error in edit method: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('bookings.index')->with('error', 'Error loading booking for editing: ' . $e->getMessage());
        }
    }

  


   


    public function destroy(Booking $booking)
{
    // Delete related service-employee relationships first
    $booking->serviceEmployees()->delete();
    
    // Then delete the booking
    $booking->delete();
    
    return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
}

   public function store(Request $request)
{
    $request->validate([
        'client_id'=>'required|exists:clients,id',
        'start_time'=>'required|date',
        'payment_status'=>'required|in:paid,unpaid',
        'services.*.service_id'=>'required|exists:services,id',
        'services.*.employee_id'=>'required|exists:employees,id',
        'services.*.price'=>'required|numeric|min:0',
    ]);

    // Create the booking
    $booking = Booking::create([
        'client_id' => $request->client_id,
        'start_time' => $request->start_time,
        'notes' => $request->notes,
        'payment_status' => $request->payment_status,
    ]);

    // Create service-employee relationships
    if ($request->has('services')) {
        foreach ($request->services as $serviceData) {
            $booking->serviceEmployees()->create([
                'service_id' => $serviceData['service_id'],
                'employee_id' => $serviceData['employee_id'],
                'price' => $serviceData['price'],
            ]);
        }
    }

    return redirect()->to('/bookings/employee?employee_id=&date=' . date('Y-m-d') . '&show_all=1')
    ->with('success', 'Appointment added successfully.');
}


public function index()
{
    return view('bookings.index');
}

public function byEmployee(Request $request)
{
    $employees = Employee::orderBy('name')->get();
    $employeeId = $request->get('employee_id');      // may be null
    $date = $request->get('date');                  // DO NOT default to today here
    $showAll = $request->boolean('show_all');       // cast to boolean properly

    $bookings = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);


    if ($showAll) {
        // All bookings (optionally for a specific employee)
        if ($employeeId) {
            $bookings = Booking::whereHas('serviceEmployees', function($q) use ($employeeId) {
                $q->where('employee_id', $employeeId);
            })->with(['client','serviceEmployees.service','serviceEmployees.employee'])
              ->orderBy('start_time','desc')->orderBy('start_time','desc')->paginate(10);
;
        } else {
            $bookings = Booking::with(['client','serviceEmployees.service','serviceEmployees.employee'])
                              ->orderBy('start_time','desc')->orderBy('start_time','desc')->paginate(10);
;
        }
    } elseif ($employeeId && $date) {
        // Employee + date
        $bookings = Booking::whereHas('serviceEmployees', function($q) use ($employeeId) {
            $q->where('employee_id', $employeeId);
        })->whereDate('start_time', $date)
          ->with(['client','serviceEmployees.service','serviceEmployees.employee'])
          ->orderBy('start_time','desc')->orderBy('start_time','desc')->paginate(10);
;
    } elseif ($date) {
        // Date only (all employees) <- THIS IS THE FIX FOR YOUR PROBLEM
        $bookings = Booking::whereDate('start_time', $date)
            ->with(['client','serviceEmployees.service','serviceEmployees.employee'])
            ->orderBy('start_time','desc')->orderBy('start_time','desc')->paginate(10);
;
    } elseif ($employeeId) {
        // Employee only (no date filter)
        $bookings = Booking::whereHas('serviceEmployees', function($q) use ($employeeId) {
            $q->where('employee_id', $employeeId);
        })->with(['client','serviceEmployees.service','serviceEmployees.employee'])
          ->orderBy('start_time','desc')->orderBy('start_time','desc')->paginate(10);
;
    }

    return view('bookings.by_employee', compact('employees','employeeId','date','showAll','bookings'));
}

public function update(Request $request, Booking $booking)
{
    // Validate request
    $validated = $request->validate([
        'client_id' => 'required|exists:clients,id',
        'start_time' => 'required|date',
        'payment_status' => 'required|in:unpaid,paid',
        'notes' => 'nullable|string',
        'services' => 'required|array',
        'services.*.service_id' => 'required|exists:services,id',
        'services.*.employee_id' => 'required|exists:employees,id',
        'services.*.price' => 'required|numeric|min:0',
    ]);

    // Update booking info
    $booking->update([
        'client_id' => $validated['client_id'],
        'start_time' => $validated['start_time'],
        'payment_status' => $validated['payment_status'],
        'notes' => $validated['notes'],
    ]);

    // Sync services & employees with price
    $booking->serviceEmployees()->delete(); // remove old entries

    foreach ($validated['services'] as $serviceData) {
        $booking->serviceEmployees()->create([
            'service_id' => $serviceData['service_id'],
            'employee_id' => $serviceData['employee_id'],
            'price' => $serviceData['price'],
        ]);
    }

    // ✅ Redirect back to your employee bookings page
    return redirect()->to('bookings/employee?employee_id=&date=2025-10-03&show_all=1')->with('success', 'Booking updated successfully.');
}



    public function schedule()
    {
        return view('bookings.schedule');
    }

   public function getEvents(Request $request)
{
    $bookings = Booking::with(['client','serviceEmployees.service','serviceEmployees.employee'])
        ->orderBy('start_time','asc')
        ->get();

    $events = [];
    foreach ($bookings as $booking) {
        $services = $booking->serviceEmployees->map(fn($se) => optional($se->service)->name)->filter()->join(', ');
        $employees = $booking->serviceEmployees->map(fn($se) => optional($se->employee)->name)->filter()->unique()->join(', ');

        $titleParts = [];
        if ($booking->client) { $titleParts[] = $booking->client->full_name; }
        if ($services) { $titleParts[] = $services; }
        if ($employees) { $titleParts[] = '@ ' . $employees; }
        $title = implode(' • ', $titleParts) ?: 'Booking #' . $booking->id;

        // Calculate end time as start_time + total service duration
        $totalMinutes = $booking->serviceEmployees->sum(fn($se) => optional($se->service)->duration ?? 60);
        $endTime = $booking->start_time->copy()->addMinutes($totalMinutes);

        $events[] = [
            'id' => $booking->id,
            'title' => $title,
            'start' => $booking->start_time->format('Y-m-d\TH:i:s'),
            'end' => $endTime->format('Y-m-d\TH:i:s'),
            'allDay' => false,
            'extendedProps' => [
                'payment_status' => $booking->payment_status,
                'notes' => $booking->notes,
            ],
        ];
    }

    return response()->json($events);
}
}
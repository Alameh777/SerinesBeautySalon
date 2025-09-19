<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
// web.php
Route::get('/dashboard', function () {
    try {
        $bookingsCount = \App\Models\Booking::count();
        $clientsCount = \App\Models\Client::count();
        $employeesCount = \App\Models\Employee::count();
        $servicesCount = \App\Models\Service::count();
        return view('dashboard', compact('bookingsCount','clientsCount','employeesCount','servicesCount'));
    } catch (\Exception $e) {
        return view('dashboard');
    }
});

Route::get('/', function() {
    return redirect('/dashboard');
});

// Get employees for a specific service
Route::get('/service/{service}/employees', [BookingController::class, 'getServiceEmployees']);

// ---------------- Clients ----------------
Route::get('clients/history/{client}', [ClientController::class, 'history'])->name('clients.history');
Route::resource('clients', ClientController::class);

// ---------------- Employees ----------------
Route::resource('employees', EmployeeController::class);

// ---------------- Services ----------------
Route::resource('services', ServiceController::class);

// ---------------- Bookings ----------------
Route::get('bookings/schedule', [BookingController::class, 'schedule'])->name('bookings.schedule');

Route::get('bookings/events', [BookingController::class, 'getEvents'])->name('bookings.getEvents');
// View bookings filtered by employee
Route::get('bookings/employee', [BookingController::class, 'byEmployee'])->name('bookings.byEmployee');

// Debug routes
Route::get('debug-booking/{id}', function($id) {
    try {
        $booking = \App\Models\Booking::with(['serviceEmployees.service', 'serviceEmployees.employee'])->find($id);
        if ($booking) {
            return response()->json([
                'booking' => $booking,
                'service_employees' => $booking->serviceEmployees,
                'count' => $booking->serviceEmployees->count()
            ]);
        } else {
            return response()->json(['error' => 'Booking not found']);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

Route::get('debug-db', function() {
    try {
        $bookings = \App\Models\Booking::all();
        $serviceEmployees = \App\Models\BookingServiceEmployee::all();
        
        return response()->json([
            'bookings_count' => $bookings->count(),
            'service_employees_count' => $serviceEmployees->count(),
            'bookings' => $bookings,
            'service_employees' => $serviceEmployees
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

Route::get('test-edit/{id}', function($id) {
    try {
        $booking = \App\Models\Booking::with(['serviceEmployees.service', 'serviceEmployees.employee'])->find($id);
        if (!$booking) {
            return 'Booking not found';
        }
        
        $clients = \App\Models\Client::orderBy('full_name')->get();
        $services = \App\Models\Service::all();
        $employees = \App\Models\Employee::with('services')->get();
        
        return 'All data loaded successfully. Booking ID: ' . $booking->id . ', Service employees: ' . $booking->serviceEmployees->count();
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage() . ' at line ' . $e->getLine();
    }
});


Route::resource('bookings', BookingController::class);

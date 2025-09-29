<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');

    $employees = Employee::when($search, function ($query, $search) {
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
    })->paginate(10); // Show 10 employees per page

    return view('employees.index', compact('employees'));
}


    public function create()
    {
        $services = Service::orderBy('name')->get();
        return view('employees.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|unique:employees,email',
            'phone'   => 'nullable|string',
            'gender'  => 'nullable|in:male,female',
            'services'=> 'nullable|array',
            'services.*' => 'exists:services,id'
        ]);

        // Save employee with all fields
        $employee = Employee::create($request->only(['name','email','phone','gender']));

        if ($request->has('services')) {
            $employee->services()->sync($request->services);
        }

        return redirect()->route('employees.index')->with('success', 'Employee added successfully.');
    }

    public function edit(Employee $employee)
    {
        $services = Service::orderBy('name')->get();
        $employeeServices = $employee->services->pluck('id')->toArray();
        return view('employees.edit', compact('employee', 'services', 'employeeServices'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|unique:employees,email,' . $employee->id,
            'phone'   => 'nullable|string',
            'gender'  => 'nullable|in:male,female',
            'services'=> 'nullable|array',
            'services.*' => 'exists:services,id',
        ]);

        $employee->update($request->only(['name','email','phone','gender']));

        $employee->services()->sync($request->services ?? []);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $hasBookings = \App\Models\BookingServiceEmployee::where('employee_id', $employee->id)->exists();
        
        if ($hasBookings) {
            return redirect()->route('employees.index')
                ->with('error', 'Cannot delete employee. This employee has existing bookings. Please remove all bookings first.');
        }
        
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}

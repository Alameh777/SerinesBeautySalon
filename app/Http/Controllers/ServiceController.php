<?php

    namespace App\Http\Controllers;
    use App\Models\Employee;
    use Illuminate\Http\Request;
    use App\Models\Service;
    class ServiceController
    {
public function index(Request $request)
{
    $query = $request->get('search');

    $services = Service::with('default_employee', 'employees') // eager load
        ->when($query, function($q) use ($query) {
            $q->where('name', 'like', "%{$query}%");
        })
        ->latest()
        ->paginate(10);

    return view('services.index', compact('services'));
}




        public function create()
    {
        $employees = Employee::orderBy('name')->get();
        return view('services.create', compact('employees'));
    }

        public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'employees' => 'nullable|array',
            'employees.*' => 'exists:employees,id',
        ]);

        $service = Service::create($request->only(['name','description','price','duration','default_employee_id']));

        // Assign employees
        if($request->has('employees')){
            $service->employees()->sync($request->employees);
        }

        return redirect()->route('services.index')->with('success','Service added successfully.');
    }


        public function edit(Service $service)
    {
        $employees = Employee::orderBy('name')->get();
        $serviceEmployees = $service->employees->pluck('id')->toArray();
        return view('services.edit', compact('service', 'employees', 'serviceEmployees'));
    }

        public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'employees' => 'nullable|array',
            'employees.*' => 'exists:employees,id',
            'default_employee_id'=>'nullable|exists:employees,id',
        ]);

        $service->update($request->only(['name','description','price','duration','default_employee_id']));

        // Update assigned employees
        $service->employees()->sync($request->employees ?? []);

        return redirect()->route('services.index')->with('success','Service updated successfully.');
    }

        public function destroy(Service $service)
        {
            // Check if service is assigned to any employees
            if ($service->employees()->exists()) {
                return redirect()->route('services.index')
                    ->with('error', 'Cannot delete service. This service is assigned to employees. Please remove all employee assignments first.');
            }
            
            // Check if service has any bookings
            $hasBookings = \App\Models\BookingServiceEmployee::where('service_id', $service->id)->exists();
            
            if ($hasBookings) {
                return redirect()->route('services.index')
                    ->with('error', 'Cannot delete service. This service has existing bookings. Please remove all bookings first.');
            }
            
            $service->delete();
            return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
        }
    }

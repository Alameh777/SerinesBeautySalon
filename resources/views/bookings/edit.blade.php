@extends('layouts.app')

@section('content')
<h2>Edit Booking</h2>

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('bookings.update', $booking) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Basic Booking Information -->
    <div style="margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h3>Booking Information</h3>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Client:</label>
            <select name="client_id" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="">--Select Client--</option>
                @foreach($clients as $client)
                <option value="{{ $client->id }}" {{ old('client_id', $booking->client_id)==$client->id?'selected':'' }}>
                    {{ $client->full_name }} ({{ $client->phone }})
                </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Start Time:</label>
            <input type="datetime-local" name="start_time" value="{{ old('start_time', $booking->start_time->format('Y-m-d\TH:i')) }}" required 
            style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Payment Status:</label>
            <select name="payment_status" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="unpaid" {{ old('payment_status', $booking->payment_status)=='unpaid'?'selected':'' }}>Unpaid</option>
                <option value="paid" {{ old('payment_status', $booking->payment_status)=='paid'?'selected':'' }}>Paid</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Notes:</label>
            <textarea name="notes" rows="3" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">{{ old('notes', $booking->notes) }}</textarea>
        </div>
    </div>

    <!-- Services Selection -->
    <div style="margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h3>Services & Employees</h3>
        <p style="color: #666; margin-bottom: 15px;">Select services and their corresponding employees for this booking.</p>
        
        <div id="services-container">
            
            @foreach($existingServices as $index => $serviceData)
            <div class="service-row" style="margin-bottom: 15px; padding: 15px; border: 1px solid #eee; border-radius: 4px;">
                <div style="display: flex; gap: 15px; align-items: end;">
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Service:</label>
                        <select name="services[{{ $index }}][service_id]" class="service-select" required 
                                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">--Select Service--</option>
                    @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ $serviceData['service_id']==$service->id?'selected':'' }}>
                                {{ $service->name }} - ${{ $service->price }} ({{ $service->duration }}min)
                        </option>
                    @endforeach
                </select>
                    </div>
                    
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Employee:</label>
                        <select name="services[{{ $index }}][employee_id]" class="employee-select" required 
                                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">--Select Employee--</option>
                    @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ $serviceData['employee_id']==$employee->id?'selected':'' }}>
                                {{ $employee->name }}
                            </option>
                    @endforeach
                </select>
                    </div>
                    
                    <div>
                        <button type="button" class="remove-service" style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;" 
                                onclick="removeServiceRow(this)" {{ count($existingServices) == 1 ? 'disabled' : '' }}>Remove</button>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
        
        <button type="button" id="add-service" style="background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
            + Add Another Service
        </button>
    </div>

    <button type="submit" style="background: #007bff; color: white; border: none; padding: 12px 30px; border-radius: 4px; cursor: pointer; font-size: 16px;">
        Update Booking
    </button>
</form>

<script>
let serviceCounter = {{ count($existingServices) }};

// Service-Employee relationship data
const serviceEmployeeData = @json($employees->mapWithKeys(function($employee) {
    return [$employee->id => $employee->services->pluck('id')->toArray()];
}));

const employeesData = @json($employees->keyBy('id'));
const servicesData = @json($services);

console.log('JavaScript loaded. Services:', servicesData);
console.log('Employees:', employeesData);
console.log('Service-Employee relationships:', serviceEmployeeData);

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up event listeners');
    
    // Add event listener to add service button
    const addServiceBtn = document.getElementById('add-service');
    console.log('Add service button found:', addServiceBtn);
    
    if (addServiceBtn) {
        addServiceBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Add service button clicked');
            addServiceRow();
        });
    } else {
        console.error('Add service button not found!');
    }
    
    // Add event listeners to existing service selects
    document.querySelectorAll('.service-select').forEach(select => {
        select.addEventListener('change', function() {
            updateEmployeeOptions(this);
        });
    });
    
    // Initialize remove buttons state
    updateRemoveButtons();
});

function addServiceRow() {
    console.log('Adding new service row, counter:', serviceCounter);
    
    const container = document.getElementById('services-container');
    if (!container) {
        console.error('Services container not found!');
        return;
    }
    
    const newRow = document.createElement('div');
    newRow.className = 'service-row';
    newRow.style.cssText = 'margin-bottom: 15px; padding: 15px; border: 1px solid #eee; border-radius: 4px;';
    
    // Build services options
    let servicesOptions = '<option value="">--Select Service--</option>';
    if (servicesData && Array.isArray(servicesData)) {
        servicesData.forEach(service => {
            servicesOptions += '<option value="' + service.id + '">' + service.name + ' - $' + service.price + ' (' + service.duration + 'min)</option>';
        });
    }
    
    // Build employees options
    let employeesOptions = '<option value="">--Select Employee--</option>';
    if (employeesData && typeof employeesData === 'object') {
        Object.keys(employeesData).forEach(employeeId => {
            const employee = employeesData[employeeId];
            employeesOptions += '<option value="' + employeeId + '">' + employee.name + '</option>';
        });
    }
    
    // Create the HTML content
    const htmlContent = 
        '<div style="display: flex; gap: 15px; align-items: end;">' +
            '<div style="flex: 1;">' +
                '<label style="display: block; margin-bottom: 5px; font-weight: bold;">Service:</label>' +
                '<select name="services[' + serviceCounter + '][service_id]" class="service-select" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">' +
                    servicesOptions +
                '</select>' +
            '</div>' +
            '<div style="flex: 1;">' +
                '<label style="display: block; margin-bottom: 5px; font-weight: bold;">Employee:</label>' +
                '<select name="services[' + serviceCounter + '][employee_id]" class="employee-select" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">' +
                    employeesOptions +
                '</select>' +
            '</div>' +
            '<div>' +
                '<button type="button" class="remove-service" style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;" onclick="removeServiceRow(this)">Remove</button>' +
            '</div>' +
        '</div>';
    
    newRow.innerHTML = htmlContent;
    container.appendChild(newRow);
    serviceCounter++;
    
    console.log('New service row added, new counter:', serviceCounter);
    
    // Update remove buttons state
    updateRemoveButtons();
    
    // Add event listener to new service select
    const newServiceSelect = newRow.querySelector('.service-select');
    if (newServiceSelect) {
        newServiceSelect.addEventListener('change', function() {
            updateEmployeeOptions(this);
        });
    }
}

function removeServiceRow(button) {
    button.closest('.service-row').remove();
    updateRemoveButtons();
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.service-row');
    const removeButtons = document.querySelectorAll('.remove-service');
    
    removeButtons.forEach((button, index) => {
        button.disabled = rows.length === 1;
    });
}

function updateEmployeeOptions(serviceSelect) {
    const employeeSelect = serviceSelect.closest('.service-row').querySelector('.employee-select');
    const selectedServiceId = parseInt(serviceSelect.value);
    
    // Clear current options except the first one
    employeeSelect.innerHTML = '<option value="">--Select Employee--</option>';
    
    if (selectedServiceId) {
        // Find employees who can perform this service
        Object.keys(serviceEmployeeData).forEach(employeeId => {
            if (serviceEmployeeData[employeeId].includes(selectedServiceId)) {
                const employeeName = employeesData[employeeId].name;
                const option = document.createElement('option');
                option.value = employeeId;
                option.textContent = employeeName;
                employeeSelect.appendChild(option);
            }
        });
    } else {
        // If no service selected, show all employees
        Object.keys(employeesData).forEach(employeeId => {
            const employeeName = employeesData[employeeId].name;
            const option = document.createElement('option');
            option.value = employeeId;
            option.textContent = employeeName;
            employeeSelect.appendChild(option);
        });
    }
}
</script>
@endsection
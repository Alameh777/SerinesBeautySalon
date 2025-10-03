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

    <!-- Booking Information -->
    <div style="margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h3>Booking Information</h3>
        
        <div style="margin-bottom: 15px;">
            <label>Client:</label>
            <select name="client_id" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
                <option value="">--Select Client--</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id', $booking->client_id)==$client->id?'selected':'' }}>
                        {{ $client->full_name }} ({{ $client->phone }})
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Start Time:</label>
            <input type="datetime-local" name="start_time" 
                value="{{ old('start_time', $booking->start_time->format('Y-m-d\TH:i')) }}" required 
                style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label>Payment Status:</label>
            <select name="payment_status" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
                <option value="unpaid" {{ old('payment_status', $booking->payment_status)=='unpaid'?'selected':'' }}>Unpaid</option>
                <option value="paid" {{ old('payment_status', $booking->payment_status)=='paid'?'selected':'' }}>Paid</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Notes:</label>
            <textarea name="notes" rows="3" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">{{ old('notes', $booking->notes) }}</textarea>
        </div>
    </div>

    <!-- Services & Employees -->
    <div style="margin-bottom: 30px; padding: 20px; border:1px solid #ddd;border-radius:5px;">
        <h3>Services & Employees</h3>
        <p style="color:#666;">Select services, employees, and set the price for this booking.</p>

        <div id="services-container">
            @foreach($booking->serviceEmployees as $index => $se)
            <div class="service-row" style="margin-bottom:15px;padding:15px;border:1px solid #eee;border-radius:4px;">
                <div style="display:flex;gap:15px;align-items:end;">
                    <div style="flex:1;">
                        <label>Service:</label>
                        <select name="services[{{ $index }}][service_id]" class="service-select" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
                            <option value="">--Select Service--</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ $se->service_id == $service->id?'selected':'' }}>
                                    {{ $service->name }} - ${{ $service->price }} ({{ $service->duration }}min)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="flex:1;">
                        <label>Employee:</label>
                        <select name="services[{{ $index }}][employee_id]" class="employee-select" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
                            <option value="">--Select Employee--</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ $se->employee_id == $employee->id?'selected':'' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="flex:0 0 120px;">
                        <label>Price:</label>
                        <input type="number" name="services[{{ $index }}][price]" class="price-input" step="0.01" min="0" 
                            value="{{ $se->price }}" required
                            style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
                    </div>

                    <div>
                        <button type="button" class="remove-service" style="background:#dc3545;color:white;border:none;padding:8px 12px;border-radius:4px;cursor:pointer;"
                                onclick="removeServiceRow(this)" {{ count($booking->serviceEmployees) == 1 ? 'disabled' : '' }}>Remove</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <button type="button" id="add-service" style="background:#28a745;color:white;border:none;padding:10px 20px;border-radius:4px;cursor:pointer;">+ Add Another Service</button>

        <!-- Total Price -->
        <div style="margin-top:20px;padding:15px;background:#f8f9fa;border:1px solid #dee2e6;border-radius:4px;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:18px;font-weight:bold;">Total Price:</span>
                <span id="total-price" style="font-size:20px;font-weight:bold;color:#007bff;"></span>
            </div>
        </div>
    </div>

    <button type="submit" style="background:#007bff;color:white;border:none;padding:12px 30px;border-radius:4px;cursor:pointer;font-size:16px;">Update Booking</button>
</form>

<script>
let serviceCounter = {{ $booking->serviceEmployees->count() }};

// JSON data from backend
const serviceEmployeeData = @json($employees->mapWithKeys(fn($e) => [$e->id => $e->services->pluck('id')->toArray()]));
const employeesData = @json($employees->keyBy('id'));
const servicesData = @json($services);

document.addEventListener('DOMContentLoaded', () => {
    calculateTotal();

    document.getElementById('add-service').addEventListener('click', e => {
        e.preventDefault();
        addServiceRow();
    });

    document.querySelectorAll('.service-select').forEach(sel => {
        sel.addEventListener('change', () => {
            updateEmployeeOptions(sel);
            updateServicePrice(sel);
        });
    });

    document.querySelectorAll('.price-input').forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    updateRemoveButtons();
});

function addServiceRow() {
    const container = document.getElementById('services-container');
    const newRow = document.createElement('div');
    newRow.className = 'service-row';
    newRow.style.cssText = 'margin-bottom:15px;padding:15px;border:1px solid #eee;border-radius:4px;';

    let servicesOptions = '<option value="">--Select Service--</option>';
    servicesData.forEach(s => servicesOptions += `<option value="${s.id}">${s.name} - $${s.price} (${s.duration}min)</option>`);

    let employeesOptions = '<option value="">--Select Employee--</option>';
    Object.values(employeesData).forEach(emp => employeesOptions += `<option value="${emp.id}">${emp.name}</option>`);

    newRow.innerHTML = `
        <div style="display:flex;gap:15px;align-items:end;">
            <div style="flex:1;">
                <label>Service:</label>
                <select name="services[${serviceCounter}][service_id]" class="service-select" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
                    ${servicesOptions}
                </select>
            </div>
            <div style="flex:1;">
                <label>Employee:</label>
                <select name="services[${serviceCounter}][employee_id]" class="employee-select" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
                    ${employeesOptions}
                </select>
            </div>
            <div style="flex:0 0 120px;">
                <label>Price:</label>
                <input type="number" name="services[${serviceCounter}][price]" class="price-input" step="0.01" min="0" value="0.00" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
            </div>
            <div>
                <button type="button" class="remove-service" style="background:#dc3545;color:white;border:none;padding:8px 12px;border-radius:4px;cursor:pointer;" onclick="removeServiceRow(this)">Remove</button>
            </div>
        </div>
    `;
    container.appendChild(newRow);

    newRow.querySelector('.service-select').addEventListener('change', e => {
        updateEmployeeOptions(e.target);
        updateServicePrice(e.target);
    });

    newRow.querySelector('.price-input').addEventListener('input', calculateTotal);

    serviceCounter++;
    updateRemoveButtons();
    calculateTotal();
}

function removeServiceRow(btn) {
    btn.closest('.service-row').remove();
    updateRemoveButtons();
    calculateTotal();
}

function updateRemoveButtons() {
    const buttons = document.querySelectorAll('.remove-service');
    const rows = document.querySelectorAll('.service-row');
    buttons.forEach(btn => btn.disabled = rows.length === 1);
}

function updateEmployeeOptions(sel) {
    const empSelect = sel.closest('.service-row').querySelector('.employee-select');
    const serviceId = parseInt(sel.value);
    empSelect.innerHTML = '<option value="">--Select Employee--</option>';
    if (serviceId) {
        Object.keys(serviceEmployeeData).forEach(empId => {
            if (serviceEmployeeData[empId].includes(serviceId)) {
                const option = document.createElement('option');
                option.value = empId;
                option.textContent = employeesData[empId].name;
                empSelect.appendChild(option);
            }
        });
    } else {
        Object.values(employeesData).forEach(emp => {
            const option = document.createElement('option');
            option.value = emp.id;
            option.textContent = emp.name;
            empSelect.appendChild(option);
        });
    }
}

function updateServicePrice(sel) {
    const priceInput = sel.closest('.service-row').querySelector('.price-input');
    const serviceId = parseInt(sel.value);
    if (serviceId) {
        const service = servicesData.find(s => s.id == serviceId);
        if (service) priceInput.value = parseFloat(service.price).toFixed(2);
    }
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.price-input').forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    document.getElementById('total-price').textContent = '$' + total.toFixed(2);
}
</script>
@endsection

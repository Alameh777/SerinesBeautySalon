@extends('layouts.app')

@section('content')
<style>
.search-select-container {
    position: relative;
}

.search-select-input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    cursor: pointer;
}

.search-select-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    max-height: 300px;
    overflow-y: auto;
    background: white;
    border: 1px solid #ccc;
    border-top: none;
    border-radius: 0 0 4px 4px;
    z-index: 1000;
    display: none;
}

.search-select-dropdown.active {
    display: block;
}

.search-select-search {
    width: 100%;
    padding: 8px;
    border: none;
    border-bottom: 1px solid #eee;
    outline: none;
    position: sticky;
    top: 0;
    background: white;
}

.search-select-option {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
}

.search-select-option:hover {
    background: #f8f9fa;
}

.search-select-option.selected {
    background: #007bff;
    color: white;
}

.search-select-no-results {
    padding: 10px;
    color: #999;
    text-align: center;
}
</style>

<h2>New Booking</h2>

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('bookings.store') }}" method="POST">
    @csrf

    <!-- Basic Booking Information -->
    <div style="margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h3>Booking Information</h3>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Client:</label>
            <div class="search-select-container">
                <input type="text" 
                       id="client-search-input" 
                       class="search-select-input" 
                       placeholder="Search or select client..." 
                       autocomplete="off"
                       readonly>
                <input type="hidden" name="client_id" id="client-id-input" required>
                <div id="client-dropdown" class="search-select-dropdown">
                    <input type="text" 
                           id="client-search-box" 
                           class="search-select-search" 
                           placeholder="Type to search...">
                    <div id="client-options"></div>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Start Time:</label>
            <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}" required 
                   style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Payment Status:</label>
            <div style="display: flex; gap: 15px;">
                <label style="display: flex; align-items: center; gap: 5px;">
                    <input type="radio" name="payment_status" value="unpaid" {{ old('payment_status', 'unpaid')=='unpaid'?'checked':'' }}>
                    Unpaid
                </label>
                <label style="display: flex; align-items: center; gap: 5px;">
                    <input type="radio" name="payment_status" value="paid" {{ old('payment_status')=='paid'?'checked':'' }}>
                    Paid
                </label>
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Notes:</label>
            <textarea name="notes" rows="3" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">{{ old('notes') }}</textarea>
        </div>
    </div>

    <!-- Services Selection -->
    <div style="margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h3>Services & Employees</h3>
        <p style="color: #666; margin-bottom: 15px;">Select services and their corresponding employees for this booking.</p>
        
        <div id="services-container">
            <div class="service-row" style="margin-bottom: 15px; padding: 15px; border: 1px solid #eee; border-radius: 4px;">
                <div style="display: flex; gap: 15px; align-items: end;">
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Service:</label>
                        <select name="services[0][service_id]" class="service-select" required 
                                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            <option value="">--Select Service--</option>
                            @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('services.0.service_id')==$service->id?'selected':'' }}>
                                {{ $service->name }} - ${{ $service->price }} ({{ $service->duration }}min)
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Employee:</label>
                        <select name="services[0][employee_id]" class="employee-select" required 
                                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            <option value="">--Select Employee--</option>
                            @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('services.0.employee_id')==$employee->id?'selected':'' }}>
                                {{ $employee->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div style="flex: 0 0 120px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Price:</label>
                        <input type="number" name="services[0][price]" class="price-input" step="0.01" min="0" 
                               value="{{ old('services.0.price', '0.00') }}" required
                               style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>
                    
                    <div>
                        <button type="button" class="remove-service" style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;" 
                                onclick="removeServiceRow(this)" disabled>Remove</button>
                    </div>
                </div>
            </div>
        </div>
        
        <button type="button" id="add-service" style="background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
            + Add Another Service
        </button>
        
        <!-- Total Price Display -->
        <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 18px; font-weight: bold;">Total Price:</span>
                <span id="total-price" style="font-size: 20px; font-weight: bold; color: #007bff;">$0.00</span>
            </div>
        </div>
    </div>

    <button type="submit" style="background: #007bff; color: white; border: none; padding: 12px 30px; border-radius: 4px; cursor: pointer; font-size: 16px;">
        Save Booking
    </button>
</form>

<script>
let serviceCounter = 1;

const employeesData = @json($employees->keyBy('id'));
const servicesData = @json($services);
const clientsData = @json($clients);

// Searchable Client Select
class SearchableSelect {
    constructor(inputId, hiddenInputId, dropdownId, searchBoxId, optionsId, data) {
        this.input = document.getElementById(inputId);
        this.hiddenInput = document.getElementById(hiddenInputId);
        this.dropdown = document.getElementById(dropdownId);
        this.searchBox = document.getElementById(searchBoxId);
        this.optionsContainer = document.getElementById(optionsId);
        this.data = data;
        this.filteredData = data;
        
        this.init();
    }
    
    init() {
        this.renderOptions();
        this.attachEvents();
        
        // Set old value if exists
        const oldValue = "{{ old('client_id') }}";
        if (oldValue) {
            const client = this.data.find(c => c.id == oldValue);
            if (client) {
                this.selectOption(client);
            }
        }
    }
    
    attachEvents() {
        // Open dropdown on input click
        this.input.addEventListener('click', (e) => {
            e.stopPropagation();
            this.dropdown.classList.add('active');
            this.searchBox.focus();
        });
        
        // Search functionality
        this.searchBox.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            this.filteredData = this.data.filter(client => 
                client.full_name.toLowerCase().includes(query) || 
                client.phone.toLowerCase().includes(query)
            );
            this.renderOptions();
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.dropdown.contains(e.target) && e.target !== this.input) {
                this.dropdown.classList.remove('active');
            }
        });
        
        // Prevent dropdown from closing when clicking inside search box
        this.searchBox.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }
    
    renderOptions() {
        this.optionsContainer.innerHTML = '';
        
        if (this.filteredData.length === 0) {
            this.optionsContainer.innerHTML = '<div class="search-select-no-results">No clients found</div>';
            return;
        }
        
        this.filteredData.forEach(client => {
            const option = document.createElement('div');
            option.className = 'search-select-option';
            if (this.hiddenInput.value == client.id) {
                option.classList.add('selected');
            }
            option.textContent = `${client.full_name} (${client.phone})`;
            option.dataset.id = client.id;
            
            option.addEventListener('click', () => {
                this.selectOption(client);
                this.dropdown.classList.remove('active');
            });
            
            this.optionsContainer.appendChild(option);
        });
    }
    
    selectOption(client) {
        this.input.value = `${client.full_name} (${client.phone})`;
        this.hiddenInput.value = client.id;
        this.searchBox.value = '';
        this.filteredData = this.data;
        this.renderOptions();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize searchable client select
    new SearchableSelect(
        'client-search-input',
        'client-id-input',
        'client-dropdown',
        'client-search-box',
        'client-options',
        clientsData
    );
    
    // Set current local time as default
    const startTimeInput = document.getElementById('start_time');
    if (startTimeInput && !startTimeInput.value) {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        startTimeInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    // Add event listener to add service button
    const addServiceBtn = document.getElementById('add-service');
    if (addServiceBtn) {
        addServiceBtn.addEventListener('click', function(e) {
            e.preventDefault();
            addServiceRow();
        });
    }

    // Event listeners for existing selects
    document.querySelectorAll('.service-select').forEach(select => {
        select.addEventListener('change', function() {
            updateServicePrice(this);
        });
    });

    // Price inputs
    document.querySelectorAll('.price-input').forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    updateRemoveButtons();
    calculateTotal();
});

function addServiceRow() {
    const container = document.getElementById('services-container');
    const newRow = document.createElement('div');
    newRow.className = 'service-row';
    newRow.style.cssText = 'margin-bottom: 15px; padding: 15px; border: 1px solid #eee; border-radius: 4px;';

    // Services options
    let servicesOptions = '<option value="">--Select Service--</option>';
    if (Array.isArray(servicesData)) {
        servicesData.forEach(service => {
            servicesOptions += `<option value="${service.id}">${service.name} - $${service.price} (${service.duration}min)</option>`;
        });
    }

    // Employees options (always show all)
    let employeesOptions = '<option value="">--Select Employee--</option>';
    Object.keys(employeesData).forEach(employeeId => {
        employeesOptions += `<option value="${employeeId}">${employeesData[employeeId].name}</option>`;
    });

    newRow.innerHTML = `
        <div style="display: flex; gap: 15px; align-items: end;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Service:</label>
                <select name="services[${serviceCounter}][service_id]" class="service-select" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    ${servicesOptions}
                </select>
            </div>
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Employee:</label>
                <select name="services[${serviceCounter}][employee_id]" class="employee-select" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    ${employeesOptions}
                </select>
            </div>
            <div style="flex: 0 0 120px;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Price:</label>
                <input type="number" name="services[${serviceCounter}][price]" class="price-input" step="0.01" min="0" value="0.00" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div>
                <button type="button" class="remove-service" style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;" onclick="removeServiceRow(this)">Remove</button>
            </div>
        </div>
    `;

    container.appendChild(newRow);
    serviceCounter++;

    updateRemoveButtons();

    newRow.querySelector('.service-select').addEventListener('change', function() {
        updateServicePrice(this);
    });
    newRow.querySelector('.price-input').addEventListener('input', calculateTotal);

    calculateTotal();
}

function removeServiceRow(button) {
    button.closest('.service-row').remove();
    updateRemoveButtons();
    calculateTotal();
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.service-row');
    document.querySelectorAll('.remove-service').forEach(button => {
        button.disabled = rows.length === 1;
    });
}

function updateServicePrice(serviceSelect) {
    const selectedServiceId = serviceSelect.value;
    const priceInput = serviceSelect.closest('.service-row').querySelector('.price-input');

    if (selectedServiceId && servicesData) {
        const service = servicesData.find(s => s.id == selectedServiceId);
        if (service) {
            priceInput.value = parseFloat(service.price).toFixed(2);
            calculateTotal();
        }
    }
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
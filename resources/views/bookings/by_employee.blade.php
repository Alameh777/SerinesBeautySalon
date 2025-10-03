@extends('layouts.app')

    @push('styles')
    <style>
        /* General Page Styles */
        .section, .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 25px 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.07);
            margin-bottom: 20px;
        }
        h2, h3 {
            color: #2c3e50;
            margin-top: 0;
            margin-bottom: 20px;
        }

        /* Form & Filter Styles */
        .filter-form {
            display: flex;
            gap: 15px;
            align-items: flex-end;
            flex-wrap: wrap;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            flex: 1; /* Make input groups flexible */
        }
        .filter-group.buttons {
            flex: 0 1 auto; /* Prevent button group from growing */
        }
        .filter-group label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #34495e;
            margin-bottom: 8px;
        }
        .filter-form select, .filter-form input[type="date"] {
            padding: 10px 12px;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            font-size: 1rem;
            height: 42px;
        }
        
        /* NEW: Container to group buttons together */
        .button-group {
            display: flex;
            gap: 10px;
        }

        /* --- BUTTON STYLES --- */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            font-size: 0.95rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            height: 42px;
            white-space: nowrap;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-primary { background-color: #e83e8c; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .btn-edit { background-color: #007bff; color: white; }
        .btn-danger { background-color: #dc3545; color: white; }
        
        /* Small buttons for the table */
        .btn-sm {
            padding: 5px 12px;
            font-size: 0.8rem;
            height: auto;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e3e3e3;
        }
        thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
        }
        tbody tr:hover { background-color: #f1f1f1; }
        .table-actions { display: flex; gap: 8px; }

        /* Badge Styles for Payment Status */
        .badge {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
        }
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #212529; }
    </style>
    @endpush

    @section('content')
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="section">
        <h2>View bookings by employee</h2>
        <form method="GET" action="{{ route('bookings.byEmployee') }}" class="filter-form">
            <div class="filter-group">
                <label for="employee_id">Employee</label>
                <select name="employee_id" id="employee_id">
                    <option value="">-- All employees --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ (string)$employeeId === (string)$emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
    <label for="date">Date</label>
    <input type="date" name="date" id="date" value="{{ $date ?? now()->format('Y-m-d') }}" />
</div>

            
            <div class="filter-group buttons">
                <label>&nbsp;</label> 
                <div class="button-group">
                    <button class="btn btn-secondary" type="submit">Filter by Date</button>
                    <button class="btn btn-primary" type="submit" name="show_all" value="1">
                        @if($employeeId)
                            All Bookings for Employee
                        @else
                            All Bookings
                        @endif
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ✅ Show results if employee, date, or show_all --}}
    @if($employeeId || $showAll || $date)
    <div class="card">
        <h3>Results</h3>
        @if($bookings->isEmpty())
            <p style="color:#666;">No bookings found for this filter.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Start Time</th>
                        <th>Client</th>
                        <th>Services</th>
                        <th>Payment</th>
                        <th>Total Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->start_time->format('Y-m-d H:i') }}</td>
                        <td>{{ $booking->client->full_name ?? '-' }}</td>
                        <td>
    @if($booking->serviceEmployees->count())
        @foreach($booking->serviceEmployees as $se)
            <span class="badge badge-black">{{ $se->service->name ?? '-' }}</span>
        @endforeach
    @else
        <span style="color:#888; font-style: italic;">No services</span>
    @endif  
</td>

                        <td>
                            <span class="badge {{ $booking->payment_status == 'paid' ? 'badge-success' : 'badge-warning' }}">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </td>
                       <td>
    ${{ number_format($booking->serviceEmployees->sum('price'), 2) }}
</td>


                        <td>
    <div class="table-actions">
        <!-- Edit -->
        <a class="btn btn-sm btn-edit" 
           href="{{ route('bookings.edit', $booking->id) }}" 
           title="Edit">
            <i class="fas fa-edit"></i> 
        </a>

        <!-- Delete -->
        <form action="{{ route('bookings.destroy', $booking->id) }}" 
              method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" 
                    title="Delete" 
                    onclick="return confirm('Are you sure you want to delete this booking?')">
                <i class="fas fa-trash-alt"></i> 
            </button>
        </form>
    </div>
</td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
    @endif
    @endsection

@extends('layouts.app')

@push('styles')
<style>
/* Dashboard cards */
.card {
    background: #fff;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    transition: all 0.25s ease;
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

/* Icon container in cards */
.icon-box {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff0f6; /* light pink background */
    color: #ff4f90;      /* pink icon color */
    font-size: 20px;
    transition: transform 0.2s ease;
}

.card:hover .icon-box {
    transform: scale(1.15);
}

/* Quick action buttons */
.btn {
    background-color: #28a745; /* Green */
    color: #fff !important;
    text-decoration: none;
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.25s ease;
    border: none;
    cursor: pointer;
}

.btn:hover {
    background-color: #218838;
    transform: translateY(-2px);
}
</style>

{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
@endpush

@section('content')
<div class="card" style="padding: 24px;">
    <h2 style="margin: 0 0 14px 0; font-weight: 600; color: var(--gray-700);">Welcome back 👋</h2>
    <p style="margin: 0; color: #666;">Here’s a quick snapshot of your salon today.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 16px; margin-top: 16px;">
    {{-- Bookings --}}
    <a href="{{ route('bookings.index') }}" class="card" style="text-decoration:none; color: inherit;">
        <div style="display:flex; align-items:center; gap:12px;">
            <div class="icon-box">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
                <div style="font-size:14px; color:#666;">Bookings</div>
                <div style="font-size:22px; font-weight:700;">{{ $bookingsCount ?? '-' }}</div>
            </div>
        </div>
    </a>

    {{-- Clients --}}
    <a href="{{ route('clients.index') }}" class="card" style="text-decoration:none; color: inherit;">
        <div style="display:flex; align-items:center; gap:12px;">
            <div class="icon-box">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div style="font-size:14px; color:#666;">Clients</div>
                <div style="font-size:22px; font-weight:700;">{{ $clientsCount ?? '-' }}</div>
            </div>
        </div>
    </a>

    {{-- Employees --}}
    <a href="{{ route('employees.index') }}" class="card" style="text-decoration:none; color: inherit;">
        <div style="display:flex; align-items:center; gap:12px;">
            <div class="icon-box">
                <i class="fas fa-user-tie"></i>
            </div>
            <div>
                <div style="font-size:14px; color:#666;">Employees</div>
                <div style="font-size:22px; font-weight:700;">{{ $employeesCount ?? '-' }}</div>
            </div>
        </div>
    </a>

    {{-- Services --}}
    <a href="{{ route('services.index') }}" class="card" style="text-decoration:none; color: inherit;">
        <div style="display:flex; align-items:center; gap:12px;">
            <div class="icon-box">
                <i class="fas fa-spa"></i>
            </div>
            <div>
                <div style="font-size:14px; color:#666;">Services</div>
                <div style="font-size:22px; font-weight:700;">{{ $servicesCount ?? '-' }}</div>
            </div>
        </div>
    </a>
</div>

<div class="section" style="margin-top: 20px;">
    <h3 style="margin-top:0; color: var(--gray-700);">Quick Actions</h3>
    <div style="display:flex; gap:10px; flex-wrap: wrap;">
        <a class="btn" href="{{ route('bookings.create') }}"><i class="fas fa-plus-circle"></i> New Booking</a>
        <a class="btn" href="{{ route('clients.create') }}"><i class="fas fa-user-plus"></i> Add Client</a>
        <a class="btn" href="{{ route('employees.create') }}"><i class="fas fa-user-tie"></i> Add Employee</a>
        <a class="btn" href="{{ route('services.create') }}"><i class="fas fa-spa"></i> Add Service</a>
    </div>
</div>
@endsection

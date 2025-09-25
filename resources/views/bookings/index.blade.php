@extends('layouts.app')

@section('fullheight', 'fullheight') <!-- enable full screen centering -->

@push('styles')
<style>
/* Card Container */
.dashboard-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.07);
    width: 100%;
    max-width: 600px;
    text-align: center;
    transition: all 0.3s ease;
}

.dashboard-card:hover {
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    transform: scale(1.02);
}


.card-body { padding: 40px 30px; }

.card-title {
    font-size: 2.25rem;
    font-weight: 700;
    color: #2c3e50;
    margin-top: 0;
    margin-bottom: 15px;
}

.card-subtitle {
    font-size: 1.1rem;
    color: #7f8c8d;
    margin-bottom: 35px;
    line-height: 1.6;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.action-buttons .btn {
    text-decoration: none;
    padding: 14px 28px;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

/* Pink Buttons for Primary Actions */
.btn-primary { background-color: #e83e8c; color: #fff !important; }
.btn-primary:hover { background-color: #d63384; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(232,62,140,0.4); }

/* Secondary Buttons (Light Gray) */
.btn-secondary { background-color: #f4f4f4; color: #34495e !important; }
.btn-secondary:hover { background-color: #e0e0e0; transform: translateY(-2px); }

/* Icons inside buttons */
.btn i { font-size: 1.1rem; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
@endpush

@section('content')
<div class="dashboard-card">
    <div class="card-body">
        <h1 class="card-title">Bookings Dashboard</h1>
        <p class="card-subtitle">Manage your appointments with ease. What would you like to do today?</p>

        <div class="action-buttons">
            <a class="btn btn-primary" href="{{ route('bookings.byEmployee') }}">
                <i class="fas fa-users"></i> View by Employee
            </a>
            <a class="btn btn-secondary" href="{{ route('bookings.create') }}">
                <i class="fas fa-plus-circle"></i> Add New Booking
            </a>
        </div>
    </div>
</div>
@endsection

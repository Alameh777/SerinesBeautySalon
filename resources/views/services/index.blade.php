@extends('layouts.app')

@push('styles')
<style>
/* Search bar + Add button wrapper */
.search-add-wrapper {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
    flex-wrap: wrap;
    align-items: center;
}

/* Fixed width for search input */
.search-add-wrapper input[type="text"] {
    width: 300px;
    padding: 12px 16px;
    border-radius: 8px;
    border: 2px solid #ccc;
    font-size: 14px;
}

/* Align buttons to match input height */
.search-add-wrapper button,
.search-add-wrapper a.btn {
    height: 44px;
}

/* Table action buttons */
.table-actions {
    display: flex;
    gap: 6px;
    align-items: center;
}

.table-actions a, 
.table-actions button {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    padding: 0;
}
/* Pagination styling */
.pagination {
    display: flex;
    gap: 4px;
    justify-content: center;
    margin-top: 15px;
}

.pagination .page-link {
    padding: 4px 8px;        /* smaller padding */
    font-size: 12px;         /* smaller font */
    border-radius: 4px;      /* slightly rounded */
    min-width: 32px;         /* uniform width */
    text-align: center;
}

.pagination .page-item.active .page-link {
    background-color: #ff4f90;
    border-color: #ff4f90;
    color: #fff;
}

.pagination .page-link:hover {
    background-color: #f4f4f5;
}

/* Optional: make all action icons same size */
.table-actions a.btn-primary {
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
}

.table-actions a.btn-primary:hover {
    background-color: #0069d9;
}

.table-actions button.btn-danger {
    background-color: #dc3545;
    color: #fff;
}

.table-actions button.btn-danger:hover {
    background-color: #c82333;
}

/* Pagination styling */
.pagination {
    display: flex;
    gap: 4px;
    justify-content: center;
    margin-top: 15px;
}

.pagination .page-link {
    padding: 4px 10px;
    font-size: 13px;
    border-radius: 6px;
}

.pagination .page-item.active .page-link {
    background-color: #ff4f90;
    border-color: #ff4f90;
    color: #fff;
}

.pagination .page-link:hover {
    background-color: #f4f4f5;
}
</style>
@endpush

@section('content')
<h1>Services</h1>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
        {{ session('error') }}
    </div>
@endif

<div class="search-add-wrapper">
    <!-- Search Form -->
    <form method="GET" style="display: flex; gap: 10px; align-items: center;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search services by name...">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <!-- Add Service Button -->
    <a href="{{ route('services.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Add Service
    </a>
</div>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Duration (min)</th>
            <th>Employees</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($services as $service)
        <tr>
            <td>{{ $service->name }}</td>
            <td>{{ $service->description }}</td>
            <td><strong>${{ number_format($service->price, 2) }}</strong></td>
            <td>{{ $service->duration }} min</td>
<td>
    @if($service->default_employee)
        <strong>Default: {{ $service->default_employee->name }}</strong><br>
    @endif

    @foreach($service->employees as $employee)
        <span>{{ $employee->name }}</span>
    @endforeach
</td>


            <td>
                <div class="table-actions">
                    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-primary" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>

                    <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" title="Delete" onclick="return confirm('Delete this service?')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top:15px;">
    {{ $services->links() }}
</div>
@endsection

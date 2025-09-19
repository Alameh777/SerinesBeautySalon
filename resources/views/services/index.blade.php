@extends('layouts.app')

@push('styles')
<style>
/* Make pagination smaller */
.pagination {
    display: flex;
    gap: 4px;
    justify-content: center;
    margin-top: 15px;
}

.pagination .page-link {
    padding: 4px 10px;      /* smaller padding */
    font-size: 13px;        /* smaller font */
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

/* Action buttons styling */
.table-actions {
    display: flex;
    gap: 6px; /* space between buttons */
}

.table-actions a, 
.table-actions button {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 6px;
    border: none;
    font-size: 16px;
    cursor: pointer;
    padding: 0;
}

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
</style>

{{-- Include Font Awesome for icons --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
@endpush

@section('content')
<h1>Services</h1>

{{-- Session messages --}}
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


{{-- Add Service Button --}}
<a href="{{ route('services.create') }}" class="btn btn-success" style="margin-bottom:10px; display:inline-block;">
    <i class="fas fa-plus"></i> Add Service
</a>

{{-- Services Table --}}
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
                @if($service->employees->count())
                    @foreach($service->employees as $employee)
                        <span class="badge badge-info">{{ $employee->name }}</span>
                    @endforeach
                @else
                    <span style="color:#888; font-style: italic;">No employees assigned</span>
                @endif
            </td>
            <td>
                <div class="table-actions">
                    {{-- Edit icon --}}
                    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-primary" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>

                    {{-- Delete icon --}}
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

{{-- Pagination --}}
<div style="margin-top:15px;">
    {{ $services->links() }}
</div>
@endsection

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
    gap: 8px;
    align-items: center;
}

.table-actions button, .table-actions a {
    width: 36px;
    height: 36px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    font-size: 16px;
}

</style>
@endpush

@section('content')
<h1>Employees</h1>

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
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search employees by name or email...">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <!-- Add Employee Button -->
    <a href="{{ route('employees.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Add Employee
    </a>
</div>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Service/s</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $employee)
        <tr>
            <td>{{ $employee->name }}</td>
            <td>{{ $employee->email ?? '-' }}</td>
            <td>{{ $employee->phone ?? '-' }}</td>
            <td>
                @if($employee->services->count())
                    @foreach($employee->services as $service)
                        <span class="badge badge-info">{{ $service->name }}</span>
                    @endforeach
                @else
                    <span style="color:#888; font-style: italic;">No services assigned</span>
                @endif
            </td>
            <td>
                <div class="table-actions">
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-primary" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Delete this employee?')">
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
    {{ $employees->links() }}
</div>
@endsection

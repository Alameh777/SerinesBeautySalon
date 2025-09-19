@extends('layouts.app')

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

<a href="{{ route('employees.create') }}" class="btn btn-success" style="margin-bottom:10px; display:inline-block;">
    <i class="fas fa-plus"></i> Add Employee
</a>

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
            <td>{{ $employee->email }}</td>
            <td>{{ $employee->phone }}</td>
            
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

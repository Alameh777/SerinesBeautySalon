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
    width: 300px; /* adjust as needed */
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

/* Optional: make all action icons same size */
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
<h1>Clients</h1>

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
    <form method="GET" style="display: flex; gap: 10px; align-items: center;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search clients by name or phone...">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <a href="{{ route('clients.create') }}" class="btn btn-success">
        Add Client
    </a>
</div>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clients as $client)
        <tr>
            <td>{{ $client->full_name }}</td>
            <td>{{ $client->phone }}</td>
            <td>
                <div class="table-actions">
                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-primary" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('clients.history', $client->id) }}" class="btn btn-sm btn-info" title="History">
                        <i class="fas fa-history"></i>
                    </a>
                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Delete this client?')">
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
    {{ $clients->links() }}
</div>
@endsection

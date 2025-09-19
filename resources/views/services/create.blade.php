@extends('layouts.app')

@section('content')
<div class="card" style="padding: 24px; max-width: 600px; margin: auto; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.08); background:white;">
    <h1 style="margin-bottom: 16px; font-weight:600; color:var(--gray-700);">
        {{ isset($service) ? 'Edit' : 'Add' }} Service
    </h1>

    @if ($errors->any())
        <div style="color:red; margin-bottom:16px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($service) ? route('services.update', $service->id) : route('services.store') }}" method="POST">
        @csrf
        @if(isset($service))
            @method('PUT')
        @endif

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px;">Name</label>
            <input type="text" name="name" value="{{ old('name', $service->name ?? '') }}" 
                   style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #ccc;">
            @error('name') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px;">Description</label>
            <textarea name="description" style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #ccc;" rows="3">{{ old('description', $service->description ?? '') }}</textarea>
            @error('description') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px;">Price</label>
            <input type="number" step="0.01" name="price" value="{{ old('price', $service->price ?? '') }}" 
                   style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #ccc;">
            @error('price') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px;">Assign Employees</label>
            @foreach($employees as $employee)
                <label style="display:block; margin-bottom:5px;">
                    <input type="checkbox" name="employees[]" value="{{ $employee->id }}"
                        {{ (isset($serviceEmployees) && in_array($employee->id, $serviceEmployees)) || in_array($employee->id, old('employees', [])) ? 'checked' : '' }}>
                    {{ $employee->name }}
                </label>
            @endforeach
            @error('employees') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:4px;">Duration (minutes)</label>
            <input type="number" name="duration" value="{{ old('duration', $service->duration ?? '') }}" 
                   style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #ccc;">
            @error('duration') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <button type="submit" style="padding:12px 24px; background-color:#28a745; color:white; border:none; border-radius:8px; font-weight:600; cursor:pointer; transition: all 0.2s;">
            {{ isset($service) ? 'Update Service' : 'Save Service' }}
        </button>
    </form>
</div>
@endsection

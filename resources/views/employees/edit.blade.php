@extends('layouts.app')

@section('content')
<div class="card" style="padding: 24px; max-width: 700px; margin: auto; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.08);">
    <h1 style="margin-bottom: 16px; font-weight:600; color:var(--gray-700);">Edit Employee</h1>

    @if ($errors->any())
        <div style="color:red; margin-bottom:16px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('employees.update', $employee->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom:14px;">
            <label for="name" style="display:block; margin-bottom:4px;">Name:</label>
            <input type="text" name="name" value="{{ old('name', $employee->name) }}" required style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #ccc;">
        </div>

        <div style="margin-bottom:14px;">
            <label for="email" style="display:block; margin-bottom:4px;">Email:</label>
            <input type="email" name="email" value="{{ old('email', $employee->email) }}" required style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #ccc;">
        </div>

        <div style="margin-bottom:14px;">
            <label for="phone" style="display:block; margin-bottom:4px;">Phone:</label>
            <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #ccc;">
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px;">Gender:</label>
            <label style="margin-right:12px;">
                <input type="radio" name="gender" value="male" {{ old('gender', $employee->gender) == 'male' ? 'checked' : '' }}> Male
            </label>
            <label>
                <input type="radio" name="gender" value="female" {{ old('gender', $employee->gender) == 'female' ? 'checked' : '' }}> Female
            </label>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:4px;">Assign Services:</label>
            <div style="display:flex; flex-wrap:wrap; gap:10px;">
                @foreach($services as $service)
                    <label style="display:flex; align-items:center; gap:6px; background:#f4f4f4; padding:6px 10px; border-radius:6px; cursor:pointer;">
                        <input type="checkbox" name="services[]" value="{{ $service->id }}" 
                            {{ $employee->services->contains($service->id) || (collect(old('services'))->contains($service->id)) ? 'checked':'' }}>
                        {{ $service->name }}
                    </label>
                @endforeach
            </div>
        </div>

        <button type="submit" style="padding:12px 24px; background-color:#28a745; color:white; border:none; border-radius:8px; font-weight:600; cursor:pointer; transition: all 0.2s;">
            Update Employee
        </button>
    </form>
</div>
@endsection

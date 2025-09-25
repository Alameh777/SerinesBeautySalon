@extends('layouts.app')

@push('styles')
<style>
/* Card container */
.form-card {
    background: #fff;
    border-radius: 12px;
    padding: 30px 24px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    max-width: 600px;
    margin: 20px auto;
}

/* Form fields */
.form-card label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #333;
}

.form-card input[type="text"],
.form-card input[type="email"],
.form-card select {
    width: 100%;
    padding: 10px 14px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 16px;
    transition: all 0.2s ease;
}

.form-card input[type="text"]:focus,
.form-card input[type="email"]:focus,
.form-card select:focus {
    border-color: #ff4f90; /* pink accent */
    box-shadow: 0 0 0 3px rgba(255,79,144,0.15);
    outline: none;
}

/* Radio buttons */
.gender-options {
    display: flex;
    gap: 20px;
    margin-bottom: 16px;
}

.gender-options label {
    font-weight: normal;
}

/* Multi-select */
.form-card select[multiple] {
    height: 120px;
}

/* Submit button */
.form-card button {
    background-color: #28a745; /* green */
    color: #fff;
    padding: 12px 20px;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.form-card button:hover {
    background-color: #218838;
    transform: translateY(-2px);
}

/* Error messages */
.error-messages {
    background: #f8d7da;
    color: #721c24;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 16px;
}
</style>
@endpush

@section('content')
<h1 style="text-align:center; margin-bottom:20px;">Add New Employee</h1>

<div class="form-card">
    @if ($errors->any())
        <div class="error-messages">
            <ul style="margin:0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('employees.store') }}" method="POST">
        @csrf

        <label for="name">Name:</label>
        <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter employee name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter email" >

        <label for="phone">Phone:</label>
        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter phone number">

        <label>Gender:</label>
        <div class="gender-options">
            <label><input type="radio" name="gender" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}> Male</label>
            <label><input type="radio" name="gender" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}> Female</label>
        </div>


        <button type="submit">Add Employee</button>
    </form>
</div>
@endsection

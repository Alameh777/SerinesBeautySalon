@extends('layouts.app')

@section('content')
<div class="card" style="padding: 24px; max-width: 600px; margin: auto; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.08); background:white;">
    <h1 style="margin-bottom: 16px; font-weight:600; color:var(--gray-700);">Add Client</h1>

    @if ($errors->any())
        <div style="color:red; margin-bottom:16px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('clients.store') }}" method="POST">
        @csrf

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px;">Full Name</label>
            <input type="text" name="full_name" value="{{ old('full_name') }}" 
                   style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #ccc;">
            @error('full_name') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px;">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" 
                   style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #ccc;">
            @error('phone') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px;">Address</label>
            <input type="text" name="address" value="{{ old('address') }}" 
                style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #ccc;">
            @error('address') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom:14px;">
            <label style="display:block; margin-bottom:4px;">Gender</label>
            <label style="margin-right:12px;">
                <input type="radio" name="gender" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}> Male
            </label>
            <label>
                <input type="radio" name="gender" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}> Female
            </label>
            @error('gender') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:4px;">Notes</label>
            <textarea name="notes" style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #ccc;" rows="4">{{ old('notes') }}</textarea>
            @error('notes') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <button type="submit" style="padding:12px 24px; background-color:#28a745; color:white; border:none; border-radius:8px; font-weight:600; cursor:pointer; transition: all 0.2s;">
            Save Client
        </button>
    </form>
</div>
@endsection

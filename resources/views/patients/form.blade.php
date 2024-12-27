{{-- form.blade.php --}}
@extends('app')

@section('title', $patient ? 'Edit Patient' : 'Add Patient')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">{{ $patient ? 'Edit Patient' : 'Add Patient' }}</h2>

        <form method="POST" action="{{ $patient ? route('patients.update', $patient->id) : route('patients.store') }}">
            @csrf
            @if ($patient)
                @method('PUT')
            @endif

            @if(auth()->user()->usertype != 2)
            <div class="form-group">
                <label for="hospital_id">Hospital</label>
                <select name="hospital_id" id="hospital_id" class="form-control" required>
                    @foreach($hospitals as $hospital)
                        <option value="{{ $hospital->id }}" 
                            {{ old('hospital_id', $patient->hospital_id ?? '') == $hospital->id ? 'selected' : '' }}>
                            {{ $hospital->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @else
                <input type="hidden" name="hospital_id" value="{{ auth()->user()->hospital_id }}">
            @endif

            <div class="form-group">
                <label for="name">Patient Name</label>
                <input type="text" name="name" id="name" class="form-control"
                    value="{{ old('name', $patient->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control"
                    value="{{ old('email', $patient->email ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control"
                    value="{{ old('phone', $patient->phone ?? '') }}">
            </div>

            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control"
                    value="{{ old('date_of_birth', $patient->date_of_birth ?? '') }}">
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select name="gender" id="gender" class="form-control">
                    <option value="">Select Gender</option>
                    <option value="Male" {{ old('gender', $patient->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $patient->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">{{ $patient ? 'Update' : 'Create' }} Patient</button>
        </form>
    </div>
@endsection
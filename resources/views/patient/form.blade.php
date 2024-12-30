{{-- patient/form.blade.php --}}
@extends('app')

@section('title', $patient ? 'Edit Patient' : 'Add Patient')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">{{ $patient ? 'Edit Patient' : 'Add Patient' }}</h2>

        <form method="POST" action="{{ $patient ? route('patient.update', $patient->id) : route('patient.store') }}">
            @csrf
            @if ($patient)
                @method('PUT')
            @endif

            <div class="form-group mb-3">
                <label for="name">Patient Name</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $patient->name ?? '') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="age">Age</label>
                <input type="number" name="age" id="age" class="form-control @error('age') is-invalid @enderror"
                    value="{{ old('age', $patient->age ?? '') }}" required>
                @error('age')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="gender">Gender</label>
                <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                    <option value="">Select Gender</option>
                    <option value="1" {{ old('gender', $patient->gender ?? '') == 1 ? 'selected' : '' }}>Male</option>
                    <option value="2" {{ old('gender', $patient->gender ?? '') == 2 ? 'selected' : '' }}>Female
                    </option>
                </select>
                @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="case">Case Type</label>
                <select name="case" id="case" class="form-control @error('case') is-invalid @enderror" required>
                    <option value="">Select Case Type</option>
                    <option value="1" {{ old('case', $patient->case ?? '') == 1 ? 'selected' : '' }}>Non Trauma
                    </option>
                    <option value="2" {{ old('case', $patient->case ?? '') == 2 ? 'selected' : '' }}>Trauma</option>
                </select>
                @error('case')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="desc">Description</label>
                <textarea name="desc" id="desc" class="form-control @error('desc') is-invalid @enderror" rows="3">{{ old('desc', $patient->desc ?? '') }}</textarea>
                @error('desc')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="arrival">Arrival Time</label>
                <input type="datetime-local" name="arrival" id="arrival"
                    class="form-control @error('arrival') is-invalid @enderror"
                    value="{{ old('arrival', $patient?->arrival ? date('Y-m-d\TH:i', strtotime($patient->arrival)) : '') }}"
                    required>

                @error('arrival')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- @if (auth()->user()->role != 2) --}}
                <div class="form-group mb-3">
                    <label for="hospital_id">Hospital</label>
                    <select name="hospital_id" id="hospital_id"
                        class="form-control @error('hospital_id') is-invalid @enderror">
                        <option value="">Select Hospital</option>
                        @foreach ($hospital as $hospital)
                            <option value="{{ $hospital->id }}"
                                {{ old('hospital_id', $patient->hospital_id ?? '') == $hospital->id ? 'selected' : '' }}>
                                {{ $hospital->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('hospital_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            {{-- @else --}}
                <input type="hidden" name="hospital_id" value="{{ auth()->user()->hospital_id }}">
            {{-- @endif --}}

            <div class="form-group mb-3">
                <label for="status">Status</label>
                <input type="number" name="status" id="status"
                    class="form-control @error('status') is-invalid @enderror"
                    value="{{ old('status', $patient->status ?? '') }}" required>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">{{ $patient ? 'Update' : 'Create' }} Patient</button>
        </form>
    </div>
@endsection

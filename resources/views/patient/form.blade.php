{{-- patient/form.blade.php --}}
@extends('app')

@section('title', $patient ? 'Edit Patient' : 'Add Patient')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">{{ $patient ? 'Edit Patient' : 'Add Patient' }}</h2>

        <form method="POST" action="{{ $patient ? route('patient.update', $patient->id) : route('patient.store') }}" enctype="multipart/form-data">
            @csrf
            @if ($patient)
                @method('PUT')
            @endif

            <div class="row">
                {{-- Basic Information --}}
                <div class="col-md-6">
                    <h4 class="mb-3">Basic Information</h4>
                    
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
                            <option value="2" {{ old('gender', $patient->gender ?? '') == 2 ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Case Information --}}
                <div class="col-md-6">
                    <h4 class="mb-3">Case Information</h4>
                    
                    <div class="form-group mb-3">
                        <label for="case">Case Type</label>
                        <select name="case" id="case" class="form-control @error('case') is-invalid @enderror" required>
                            <option value="">Select Case Type</option>
                            <option value="1" {{ old('case', $patient->case ?? '') == 1 ? 'selected' : '' }}>Non Trauma</option>
                            <option value="2" {{ old('case', $patient->case ?? '') == 2 ? 'selected' : '' }}>Trauma</option>
                        </select>
                        @error('case')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="time_incident">Time of Incident</label>
                        <input type="datetime-local" name="time_incident" id="time_incident"
                            class="form-control @error('time_incident') is-invalid @enderror"
                            value="{{ old('time_incident', $patient?->time_incident ? date('Y-m-d\TH:i', strtotime($patient->time_incident)) : '') }}"
                            required>
                        @error('time_incident')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="mechanism">Mechanism of Injury/Illness</label>
                        <textarea name="mechanism" id="mechanism" class="form-control @error('mechanism') is-invalid @enderror" rows="3" required>{{ old('mechanism', $patient->mechanism ?? '') }}</textarea>
                        @error('mechanism')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                {{-- Medical Information --}}
                <div class="col-md-6">
                    <h4 class="mb-3">Medical Information</h4>

                    <div class="form-group mb-3">
                        <label for="injury">Injury/Condition Details</label>
                        <textarea name="injury" id="injury" class="form-control @error('injury') is-invalid @enderror" rows="3" required>{{ old('injury', $patient->injury ?? '') }}</textarea>
                        @error('injury')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="photo_injury">Injury Photo</label>
                        <input type="file" name="photo_injury" id="photo_injury" class="form-control @error('photo_injury') is-invalid @enderror"
                            accept="image/*">
                        @error('photo_injury')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($patient && $patient->photo_injury)
                            <div class="mt-2">
                                <img src="{{ asset($patient->photo_injury) }}" alt="Injury Photo" class="img-thumbnail" style="max-height: 200px">
                            </div>
                        @endif
                    </div>

                    <div class="form-group mb-3">
                        <label for="treatment">Treatment Given</label>
                        <textarea name="treatment" id="treatment" class="form-control @error('treatment') is-invalid @enderror" rows="3" required>{{ old('treatment', $patient->treatment ?? '') }}</textarea>
                        @error('treatment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Administrative Information --}}
                <div class="col-md-6">
                    <h4 class="mb-3">Administrative Information</h4>

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

                    <div class="form-group mb-3">
                        <label for="desc">Additional Notes</label>
                        <textarea name="desc" id="desc" class="form-control @error('desc') is-invalid @enderror" rows="3" required>{{ old('desc', $patient->desc ?? '') }}</textarea>
                        @error('desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="hospital_id">Hospital</label>
                        <select name="hospital_id" id="hospital_id" class="form-control @error('hospital_id') is-invalid @enderror">
                            <option value="">Select Hospital</option>
                            @foreach ($hospital as $h)
                                <option value="{{ $h->id }}"
                                    {{ old('hospital_id', $patient->hospital_id ?? '') == $h->id ? 'selected' : '' }}>
                                    {{ $h->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('hospital_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="">Select Status</option>
                            <option value="1" {{ old('status', $patient->status ?? '') == 1 ? 'selected' : '' }}>Menuju RS</option>
                            <option value="2" {{ old('status', $patient->status ?? '') == 2 ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <button type="submit" class="btn btn-success">{{ $patient ? 'Update' : 'Create' }} Patient</button>
                    <a href="{{ route('patient.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection
{{-- form.blade.php --}}
@extends('app')

@section('title', $user ? 'Edit User' : 'Add User')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">{{ $user ? 'Edit User' : 'Add User' }}</h2>

        <form method="POST" action="{{ $user ? route('users.update', $user->id) : route('users.store') }}">
            @csrf
            @if ($user)
                @method('PUT')
            @endif

            <div class="form-group mb-3">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" 
                    value="{{ old('username', $user->username ?? '') }}" required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                    value="{{ old('email', $user->email ?? '') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" 
                    {{ $user ? '' : 'required' }}>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if($user)
                    <small class="text-muted">Leave empty to keep current password</small>
                @endif
            </div>

            @if(auth()->user()->usertype == 3)
            <div class="form-group mb-3">
                <label for="usertype">User Type</label>
                <select name="usertype" id="usertype" class="form-control @error('usertype') is-invalid @enderror" required>
                    <option value="">Select Type</option>
                    <option value="1" {{ old('usertype', $user->usertype ?? '') == 1 ? 'selected' : '' }}>Ambulance Operator</option>
                    <option value="2" {{ old('usertype', $user->usertype ?? '') == 2 ? 'selected' : '' }}>Hospital Operator</option>
                    <option value="3" {{ old('usertype', $user->usertype ?? '') == 3 ? 'selected' : '' }}>Operator System</option>
                </select>
                @error('usertype')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3" id="hospital-select">
                <label for="hospital_id">Hospital</label>
                <select name="hospital_id" id="hospital_id" class="form-control @error('hospital_id') is-invalid @enderror">
                    <option value="">Select Hospital</option>
                    @foreach($hospitals as $hospital)
                        <option value="{{ $hospital->id }}" 
                            {{ old('hospital_id', $user->hospital_id ?? '') == $hospital->id ? 'selected' : '' }}>
                            {{ $hospital->name }}
                        </option>
                    @endforeach
                </select>
                @error('hospital_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @else
                <input type="hidden" name="usertype" value="2">
                <input type="hidden" name="hospital_id" value="{{ auth()->user()->hospital_id }}">
            @endif

            <button type="submit" class="btn btn-success">{{ $user ? 'Update' : 'Create' }} User</button>
        </form>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            function toggleHospitalSelect() {
                if ($('#usertype').val() == '2') {
                    $('#hospital-select').show();
                    $('#hospital_id').prop('required', true);
                } else {
                    $('#hospital-select').hide();
                    $('#hospital_id').prop('required', false);
                    $('#hospital_id').val('');
                }
            }

            $('#usertype').change(toggleHospitalSelect);
            toggleHospitalSelect();
        });
    </script>
    @endpush
@endsection
@extends('app')

@section('title', $user ? 'Edit User' : 'Add User')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">{{ $user ? 'Edit Nurse' : 'Add Nurse' }}</h2>

        {{-- Form --}}
        <form method="POST" action="{{ $user ? route('user.update', $user->id) : route('user.store') }}">
            @csrf
            @if ($user)
                @method('PUT')
            @endif

            {{-- Name --}}
            <div class="form-group mb-3">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $user->name ?? '') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $user->email ?? '') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Phone --}}
            <div class="form-group mb-3">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                    value="{{ old('phone', $user->phone ?? '') }}">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" 
                    class="form-control @error('password') is-invalid @enderror"
                    {{ $user ? '' : 'required' }}>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if($user)
                    <small class="text-muted">Leave empty to keep current password</small>
                @endif
            </div>

            {{-- Role (Hanya System Admin yang bisa memilih Role) --}}
            {{-- @if(auth()->user()->role == 1)
            <div class="form-group mb-3">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                    <option value="">Select Role</option>
                    <option value="1" {{ old('role', $user->role ?? '') == 1 ? 'selected' : '' }}>System Admin</option>
                    <option value="2" {{ old('role', $user->role ?? '') == 2 ? 'selected' : '' }}>Hospital Admin</option>
                    <option value="3" {{ old('role', $user->role ?? '') == 3 ? 'selected' : '' }}>Nurse</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div> --}}

            {{-- Hospital (Jika Role = Hospital Admin atau Nurse) --}}
            {{-- <div class="form-group mb-3" id="hospital-select">
                <label for="user_id">Hospital</label>
                <select name="user_id" id="user_id" 
                    class="form-control @error('user_id') is-invalid @enderror">
                    <option value="">Select Hospital</option>
                    @foreach($hospital as $h)
                        <option value="{{ $h->admin_id }}" 
                            {{ old('user_id', $user->user_id ?? '') == $h->admin_id ? 'selected' : '' }}>
                            {{ $h->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div> --}}
            {{-- @else --}}
                {{-- Jika bukan System Admin, atur default --}}
                <input type="hidden" name="role" value="3">
                <input type="hidden" name="user_id" value="{{ auth()->user()->user_id }}">
            {{-- @endif --}}

            {{-- Submit Button --}}
            <button type="submit" class="btn btn-success">{{ $user ? 'Update' : 'Create' }} User</button>
        </form>
    </div>

    {{-- JavaScript untuk Dinamis Hospital Select --}}
    {{-- @push('scripts')
    <script>
        $(document).ready(function () {
            function toggleHospitalSelect() {
                let role = $('#role').val();
                if (role == '2' || role == '3') {
                    $('#hospital-select').show();
                    $('#user_id').prop('required', true);
                } else {
                    $('#hospital-select').hide();
                    $('#user_id').prop('required', false);
                    $('#user_id').val('');
                }
            }

            $('#role').change(toggleHospitalSelect);
            toggleHospitalSelect();
        });
    </script>
    @endpush --}}
@endsection

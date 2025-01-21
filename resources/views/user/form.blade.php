@extends('app')

@section('title', $user ? 'Edit User' : 'Add User')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">{{ $user ? 'Edit Nurse' : 'Add Nurse' }}</h2>

        <form method="POST" action="{{ $user ? route('user.update', $user->id) : route('user.store') }}">
            @csrf
            @if ($user)
                @method('PUT')
            @endif

            <div class="form-group mb-3">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $user->name ?? '') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" id="email"
                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email ?? '') }}"
                    required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone"
                    class="form-control @error('phone') is-invalid @enderror"
                    value="{{ old('phone', $user->phone ?? '') }}">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="team">Team</label>
                <select name="team" id="team" class="form-control @error('team') is-invalid @enderror" required>
                    <option value="" disabled {{ old('team', $user->team ?? '') == '' ? 'selected' : '' }}>-- Select Team --</option>
                    <option value="TGC Puskemas" {{ old('team', $user->team ?? '') == 'TGC Puskemas' ? 'selected' : '' }}>TGC Puskemas</option>
                    <option value="PSC 119 Kota" {{ old('team', $user->team ?? '') == 'PSC 119 Kota' ? 'selected' : '' }}>PSC 119 Kota</option>
                </select>
                @error('team')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" id="password"
                    class="form-control @error('password') is-invalid @enderror" {{ $user ? '' : 'required' }}>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if ($user)
                    <small class="text-muted">Leave empty to keep current password</small>
                @endif
            </div>

            <input type="hidden" name="role" value="3">
            <input type="hidden" name="user_id" value="{{ auth()->user()->user_id }}">
            <button type="submit" class="btn btn-success mb-3">{{ $user ? 'Update' : 'Create' }} User</button>
        </form>
        <a href="{{ route('user.index') }}">
            <button class="btn btn-danger">Cancel</button>
        </a>
    </div>
@endsection

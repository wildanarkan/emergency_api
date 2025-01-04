{{-- hospital/form.blade.php --}}
@extends('app')

@section('title', $hospital ? 'Edit Hospital' : 'Add Hospital')


@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">{{ $hospital ? 'Edit Hospital' : 'Add Hospital' }}</h2>
        <hr>

        <form method="POST" action="{{ $hospital ? route('hospital.update', $hospital->id) : route('hospital.store') }}">
            @csrf
            @if ($hospital)
                @method('PUT')
            @endif

            {{-- Hospital Information --}}
            <h4 class="mb-3">Hospital Information</h4>
            <div class="form-group mb-3">
                <label for="name">Hospital Name</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $hospital->name ?? '') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="phone">Hospital Phone</label>
                <input type="text" name="phone" id="phone"
                    class="form-control @error('phone') is-invalid @enderror"
                    value="{{ old('phone', $hospital->phone ?? '') }}" required>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="address">Hospital Address</label>
                <input type="text" name="address" id="address"
                    class="form-control @error('address') is-invalid @enderror"
                    value="{{ old('address', $hospital->address ?? '') }}" required>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- New Type Field --}}
            <div class="form-group mb-3">
                <label for="type">Hospital Type</label>
                <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                    <option value="A" {{ old('type', $hospital->type ?? '') == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ old('type', $hospital->type ?? '') == 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ old('type', $hospital->type ?? '') == 'C' ? 'selected' : '' }}>C</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Admin Information hanya ditampilkan saat membuat hospital baru --}}
            @if (!$hospital)
                <h4 class="mt-4 mb-3">Admin Hospital</h4>
                <div class="form-group mb-3">
                    <label for="admin_name">Admin Name</label>
                    <input type="text" name="admin_name" id="admin_name"
                        class="form-control @error('admin_name') is-invalid @enderror" value="{{ old('admin_name') }}"
                        required>
                    @error('admin_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="admin_email">Admin Email</label>
                    <input type="email" name="admin_email" id="admin_email"
                        class="form-control @error('admin_email') is-invalid @enderror" value="{{ old('admin_email') }}"
                        required>
                    @error('admin_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="admin_phone">Admin Phone</label>
                    <input type="text" name="admin_phone" id="admin_phone"
                        class="form-control @error('admin_phone') is-invalid @enderror" value="{{ old('admin_phone') }}"
                        required>
                    @error('admin_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="admin_password">Admin Password</label>
                    <input type="password" name="admin_password" id="admin_password"
                        class="form-control @error('admin_password') is-invalid @enderror" required>
                    @error('admin_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            <button type="submit" class="btn btn-success mb-3">{{ $hospital ? 'Update' : 'Create' }} Hospital</button>
        </form>
        <a href="{{ route('hospital.index') }}">
            <button class="btn btn-danger">Cancel</button>
        </a>
    </div>
@endsection

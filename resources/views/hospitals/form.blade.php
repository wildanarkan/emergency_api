@extends('app')

@section('title', $hospital ? 'Edit Hospital' : 'Add Hospital')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">{{ $hospital ? 'Edit Hospital' : 'Add Hospital' }}</h2>

        <form method="POST" action="{{ $hospital ? route('hospitals.update', $hospital->id) : route('hospitals.store') }}">
            @csrf
            @if ($hospital)
                @method('PUT')
            @endif
            <div class="form-group">
                <label for="name">Hospital Name</label>
                <input type="text" name="name" id="name" class="form-control"
                    value="{{ old('name', $hospital->name ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" id="address" class="form-control"
                    value="{{ old('address', $hospital->address ?? '') }}" required>
            </div>
            <button type="submit" class="btn btn-success">{{ $hospital ? 'Update' : 'Create' }} Hospital</button>
        </form>
    </div>
@endsection

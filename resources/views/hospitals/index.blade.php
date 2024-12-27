@extends('app')

@section('title', 'Hospitals')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Hospitals</h2>
        <a href="{{ route('hospitals.create') }}" class="btn btn-primary mb-3">Add New Hospital</a>
        
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hospitals as $hospital)
                                <tr>
                                    <td>{{ $hospital->name }}</td>
                                    <td>{{ $hospital->address }}</td>
                                    <td>
                                        <a href="{{ route('hospitals.edit', $hospital->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('hospitals.destroy', $hospital->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
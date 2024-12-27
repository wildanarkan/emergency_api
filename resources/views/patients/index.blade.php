@extends('app')

@section('title', 'Patients')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Patients</h2>
        <a href="{{ route('patients.create') }}" class="btn btn-primary mb-3">Add New Patient</a>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Date of Birth</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patients as $patient)
                                <tr>
                                    <td>{{ $patient->name }}</td>
                                    <td>{{ $patient->email }}</td>
                                    <td>{{ $patient->phone ?? '-' }}</td>
                                    <td>{{ $patient->gender ?? '-' }}</td>
                                    <td>{{ $patient->date_of_birth ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('patients.edit', $patient->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST"
                                            class="d-inline">
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

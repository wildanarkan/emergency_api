{{-- patient/index.blade.php --}}
@extends('app')

@section('title', 'Patients')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Patients</h2>
        {{-- <a href="{{ route('patient.create') }}" class="btn btn-primary mb-3">Add New Patient</a> --}}

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Case Type</th>
                                <th>Description</th>
                                <th>Arrival</th>
                                <th>Hospital</th>
                                <th>Status</th>
                                @if (auth()->user()->role == 2)
                                    <th>Actions</th>
                                @endif

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patient as $patient)
                                <tr>
                                    <td>{{ $patient->name }}</td>
                                    <td>{{ $patient->age }}</td>
                                    <td>{{ $patient->gender == 1 ? 'Male' : 'Female' }}</td>
                                    <td>{{ $patient->case == 1 ? 'Non Trauma' : 'Trauma' }}</td>
                                    <td>{{ $patient->desc }}</td>
                                    <td>{{ $patient->arrival }}</td>
                                    <td>{{ $patient->hospital->name ?? '-' }}</td>
                                    <td> {{ $patient->status == 1 ? 'Menuju lokasi' : ($patient->status == 2 ? 'Rujukan' : 'Selesai') }}
                                    </td>

                                    @if (auth()->user()->role == 2)
                                        <td>
                                            {{-- Tombol Update Status --}}
                                            <form action="{{ route('patient.updateStatus', $patient->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin mengubah status pasien ini menjadi Selesai?')">
                                                    Selesai
                                                </button>
                                            </form>
                                        </td>
                                    @endif


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

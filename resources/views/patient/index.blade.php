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
                                <th>Time Incident</th>
                                <th>Mechanism</th>
                                <th>Injury</th>
                                <th>Photo</th>
                                <th>Treatment</th>
                                <th style="max-width: 400px; text-overflow: ellipsis; overflow: hidden;">Description</th>
                                <th>Arrival</th>
                                @if (auth()->user()->role != 2)
                                    <th>Hospital</th>
                                @endif
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
                                    <td>{{ \Carbon\Carbon::parse($patient->time_incident)->format('d M Y : H:i') }}</td>
                                    <td>{{ $patient->mechanism }}</td>
                                    <td>{{ $patient->injury }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#photoModal" data-photo="{{ asset($patient->photo_injury) }}">
                                            Lihat
                                        </button>

                                    </td>
                                    <td>{{ $patient->treatment }}</td>
                                    <td style="max-width: 400px; text-overflow: ellipsis; overflow: hidden;">
                                        {{ $patient->desc }}</td>
                                    <td>{{ \Carbon\Carbon::parse($patient->arrival)->format('d M Y : H:i') }}</td>
                                    @if (auth()->user()->role != 2)
                                        <td>{{ $patient->hospital->name ?? '-' }}</td>
                                    @endif
                                    <td>
                                        {{ $patient->status == 1 ? 'Menuju RS' : 'Selesai' }}
                                    </td>
                                    @if (auth()->user()->role == 2)
                                        <td>
                                            <form action="{{ route('patient.updateStatus', $patient->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm"
                                                    @disabled($patient->status != 1)
                                                    onclick="return confirm('Apakah Anda yakin ingin mengubah status pasien ini menjadi Selesai?')">
                                                    Terima
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

    <!-- Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photoModalLabel">Photo of Injury</h5>
                    <button id='closeModal' type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
                <div class="modal-body text-center">
                    <img id="photoModalImage" src="" alt="Photo of Injury" class="modal-image">
                </div>
            </div>
        </div>
    </div>
@endsection

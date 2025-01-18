@extends('app')

@section('title', 'Patients')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Patients</h2>

        {{-- Filter section --}}
        {{-- Filter dropdown trigger with right alignment --}}
        <div class="d-flex justify-content-end mb-3">
            <div class="dropdown">
                <button class="btn btn-light text-dark" type="button" id="filterDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false" style="border: 1px solid #00000063; border-radius: 5px;">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>

                {{-- Filter content with border --}}
                <div class="dropdown-menu p-3 shadow-lg border" style="width: 300px;">
                    <form action="{{ route('patient.index') }}" method="GET">

                        @if (auth()->user()->role != 2)
                            <div class="mb-3">
                                <select name="hospital_id" class="form-select border">
                                    <option value="">-- Filter by Hospital --</option>
                                    @foreach ($hospitals as $hospital)
                                        <option value="{{ $hospital->id }}"
                                            {{ request('hospital_id') == $hospital->id ? 'selected' : '' }}>
                                            {{ $hospital->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-3">
                            <select name="user_id" class="form-select border">
                                <option value="">-- Filter by Nurse --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <select name="case_type" class="form-select border">
                                <option value="">-- Filter by Case Type --</option>
                                <option value="1" {{ request('case_type') == '1' ? 'selected' : '' }}>Non Trauma
                                </option>
                                <option value="2" {{ request('case_type') == '2' ? 'selected' : '' }}>Trauma</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <select name="status" class="form-select border">
                                <option value="">-- Filter by Status --</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Menuju RS</option>
                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Apply Filter</button>
                            <a href="{{ route('patient.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Filter section --}}

        {{-- Table section --}}
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="s-table">Case Type</th>
                                <th class="m-table">Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th class="m-table">Time Incident</th>
                                <th class="x-table">Mechanism</th>
                                <th class="m-table">Injury</th>
                                <th>Photo</th>
                                <th class="x-table">Symptom</th>
                                <th class="m-table">Treatment</th>
                                <th class="m-table">Arrival</th>
                                @if (auth()->user()->role != 2)
                                    <th class="s-table">Hospital</th>
                                @endif
                                <th class="s-table">Nurse</th>
                                <th class="x-table">Request</th>
                                <th class="s-table">Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patients as $patient)
                                <tr>
                                    <td>{{ $patient->case == 1 ? 'Non Trauma' : 'Trauma' }}</td>
                                    <td>{{ $patient->name ?? '-' }}</td>
                                    <td>{{ $patient->age ?? '-' }}</td>
                                    <td>{{ $patient->gender == 1 ? 'Male' : 'Female' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($patient->time_incident)->format('d M Y : H:i') ?? '-' }}
                                    </td>
                                    <td>{{ $patient->mechanism ?? '-' }}</td>
                                    <td>{{ $patient->injury ?? '-' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#photoModal" data-photo="{{ asset($patient->photo_injury) }}">
                                            Lihat
                                        </button>
                                    </td>
                                    <td>{{ $patient->symptom ?? '-' }}</td>
                                    <td>{{ $patient->treatment ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($patient->arrival)->format('d M Y : H:i') ?? '-' }}</td>
                                    @if (auth()->user()->role != 2)
                                        <td>{{ $patient->hospital->name ?? '-' }}</td>
                                    @endif
                                    <td>{{ $patient->user->name ?? '-' }}</td>
                                    <td>{{ $patient->request ?? '-' }}</td>
                                    <td>{{ $patient->status == 1 ? 'Menuju RS' : 'Selesai' }}</td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 col">
                                            @if (auth()->user()->role == 2)
                                                <form action="{{ route('patient.updateStatus', $patient->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-success btn-sm"
                                                        @disabled($patient->status != 1)
                                                        onclick="return confirm('Apakah Anda yakin ingin mengubah status pasien ini menjadi Selesai?')">
                                                        Terima
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('patient.pdf', $patient->id) }}" class="btn btn-info btn-sm">
                                                Download
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- Table section --}}
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

@push('scripts')
    <script>
        // Prevent dropdown from closing when clicking inside
        document.querySelector('.dropdown-menu').addEventListener('click', function(e) {
            e.stopPropagation();
        });
    </script>
@endpush
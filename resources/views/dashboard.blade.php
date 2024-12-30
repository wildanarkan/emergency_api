@extends('app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Dashboard</h2>

        <hr>

        <h3 class="mb-4">All Count Data</h3>
        <!-- Cards -->
        <div class="row">
            <!-- Card Hospitals -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Hospitals</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $hospitalAllCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hospital fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Patients -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Patients</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $patientAllCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Users -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userAllCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-md fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

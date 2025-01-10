<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency - @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    @stack('styles')
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="bg-dark text-white">
            <div class="sidebar-header p-3">
                @if (auth()->user()->role == 2)
                    <h3>{{ $hospitalName = Auth::user()->hospital->name ?? 'No hospital' }}</h3>
                    <hr>
                @endif
                <h5>{{ Auth::user()->name }}</h5>

                <h6>
                    @switch(Auth::user()->role)
                        @case(1)
                            System Admin
                        @break

                        @case(2)
                            Hospital Admin
                        @break

                        @case(3)
                            Nurse
                        @break
                    @endswitch
                </h6>
            </div>

            <ul class="list-unstyled components">
                @if (auth()->user()->role == 1)
                    <a href="{{ route('dashboard') }}" class="text-white text-decoration-none">
                        <li class="px-2 {{ Request::is('dashboard') || Request::is('/') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </li>
                    </a>

                    <a href="{{ route('hospital.index') }}" class="text-white text-decoration-none">
                        <li class="px-2 {{ Request::is('hospital*') ? 'active' : '' }}">
                            <i class="fas fa-hospital me-2"></i> Hospitals
                        </li>
                    </a>

                    <a href="{{ route('user.index') }}" class="text-white text-decoration-none">
                        <li class="px-2 {{ Request::is('user*') ? 'active' : '' }}">
                            <i class="fas fa-user-md me-2"></i> Users
                        </li>
                    </a>
                @endif

                <a href="{{ route('patient.index') }}" class="text-white text-decoration-none">
                    <li class="px-2 {{ Request::is('patient*') ? 'active' : '' }}">
                        <i class="fas fa-user me-2"></i> Patients
                    </li>
                </a>

            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-dark">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="dropdown ms-auto">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            {{-- <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li> --}}
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
            });
        });

        $('#photoModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Tombol yang ditekan
            var photoUrl = button.data('photo'); // Mengambil data-photo dari tombol
            var modal = $(this);
            modal.find('#photoModalImage').attr('src', photoUrl); // Menetapkan URL gambar ke <img> di dalam modal
        });
    </script>

    @stack('scripts')
</body>

</html>

<style>
    .wrapper {
        display: flex;
    }

    #sidebar {
        min-width: 250px;
        max-width: 250px;
        min-height: 100vh;
        transition: all 0.3s;
    }

    #sidebar.active {
        margin-left: -250px;
    }

    #content {
        width: 100%;
        min-height: 100vh;
        overflow: scroll;
        display: block;
        overflow-x: visible;
        white-space: nowrap;
    }

    .table-responsive {}

    .sidebar-header {
        padding: 20px;
        background: #343a40;
    }

    .components li {
        padding: 10px 0;
    }

    .components li.active,
    .components li:hover {
        background: #495057;
    }

    .modal-image {
        max-width: 800px;
        max-height: 400px;
        width: 100%;
        object-fit: contain;
    }
</style>

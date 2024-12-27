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
                <h3>{{ Auth::user()->username }}</h3>
                <h6>
                    @switch( Auth::user()->usertype)
                        @case(1)
                            Ambulance Operator
                        @break

                        @case(2)
                            Hospital Operator
                        @break

                        @case(3)
                            Operator System
                        @break
                    @endswitch
                </h6>
            </div>

            <ul class="list-unstyled components p-3">
                <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="text-white text-decoration-none">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>

                {{-- @if (auth()->user()->usertype == 3) --}}
                <li class="{{ Request::is('hospitals*') ? 'active' : '' }}">
                    <a href="{{ route('hospitals.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-hospital me-2"></i> Hospitals
                    </a>
                </li>
                {{-- @endif --}}

                <li class="{{ Request::is('patients*') ? 'active' : '' }}">
                    <a href="{{ route('patients.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-users me-2"></i> Patients
                    </a>
                </li>

                <li class="{{ Request::is('users*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-user me-2"></i> Users
                    </a>
                </li>

                {{-- @if (auth()->user()->usertype == 3)
                    <li class="{{ Request::is('users*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}" class="text-white text-decoration-none">
                            <i class="fas fa-user-cog me-2"></i> Users
                        </a>
                    </li>
                @endif --}}
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
                            {{ Auth::user()->username }}
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
    }

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
</style>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/logo_sipongga.png') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">



    {{-- @stack('styles') --}}
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            @if (auth()->user()->role == 2)
                <h4 class="mb-3">{{ $hospitalName = Auth::user()->hospital->name ?? 'No hospital' }}</h4>
                <hr class="my-2">
            @endif
            <h5 class="mb-2">{{ Auth::user()->name }}</h5>
            <h6 class="mb-0">
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

        <ul class="sidebar-menu">
            @if (auth()->user()->role == 1)
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="{{ Request::is('dashboard') || Request::is('/') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('hospital.index') }}" class="{{ Request::is('hospital*') ? 'active' : '' }}">
                        <i class="fas fa-hospital"></i>
                        <span>Hospitals</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.index') }}" class="{{ Request::is('user*') ? 'active' : '' }}">
                        <i class="fas fa-user-md"></i>
                        <span>Users</span>
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('patient.index') }}" class="{{ Request::is('patient*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>Patients</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Navbar -->
    <nav class="main-navbar px-3 d-flex align-items-center">
        <button type="button" id="sidebarCollapse" class="btn btn-light">
            <i class="fas fa-bars"></i>
        </button>

        <div class="ms-auto d-flex align-items-center">
            <button class="btn btn-light me-3 position-relative">
                <i class="fas fa-bell"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    5
                    <span class="visually-hidden">unread messages</span>
                </span>
            </button>

            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
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
    <main class="main-content">
        <div class="container-fluid p-4">
            @yield('content')
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Handle sidebar toggle
            $('#sidebarCollapse').on('click', function() {
                const sidebar = $('#sidebar');
                const mainNavbar = $('.main-navbar');
                const mainContent = $('.main-content');

                if (window.innerWidth <= 768) {
                    sidebar.toggleClass('mobile-active');
                } else {
                    sidebar.toggleClass('collapsed');
                    mainNavbar.toggleClass('full-width');
                    mainContent.toggleClass('full-width');
                }
            });

            // Handle window resize
            $(window).resize(function() {
                if (window.innerWidth <= 768) {
                    $('#sidebar').removeClass('collapsed').addClass('mobile-active');
                    $('.main-navbar, .main-content').addClass('full-width');
                } else {
                    $('#sidebar').removeClass('mobile-active');
                    if (!$('#sidebar').hasClass('collapsed')) {
                        $('.main-navbar, .main-content').removeClass('full-width');
                    }
                }
            });
        });

        // Photo modal handler
        $('#photoModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const photoUrl = button.data('photo');
            $(this).find('#photoModalImage').attr('src', photoUrl);
        });
    </script>

    @stack('scripts')
</body>

</html>
<style>
    :root {
        --sidebar-width: 250px;
        --navbar-height: 60px;
        --primary-bg: #2c3e50;
        --secondary-bg: #34495e;
    }

    body {
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* Sidebar Styles */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: var(--sidebar-width);
        background: var(--primary-bg);
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .sidebar.collapsed {
        margin-left: calc(-1 * var(--sidebar-width));
    }

    .sidebar-header {
        padding: 1.5rem;
        background: var(--secondary-bg);
        color: white;
    }

    .sidebar-menu {
        padding: 0;
        list-style: none;
    }

    .sidebar-menu a {
        display: block;
        padding: 0.8rem 1.5rem;
        color: #ecf0f1;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .sidebar-menu a:hover,
    .sidebar-menu a.active {
        background: var(--secondary-bg);
        padding-left: 1.8rem;
    }

    .sidebar-menu i {
        width: 25px;
    }

    /* Navbar Styles */
    .main-navbar {
        position: fixed;
        top: 0;
        right: 0;
        left: var(--sidebar-width);
        height: var(--navbar-height);
        background: var(--secondary-bg);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        z-index: 999;
    }

    .main-navbar.full-width {
        left: 0;
    }

    /* Main Content Styles */
    .main-content {
        margin-left: var(--sidebar-width);
        padding-top: var(--navbar-height);
        min-height: 100vh;
        transition: all 0.3s ease;
    }

    .main-content.full-width {
        margin-left: 0;
    }

    .modal-image {
        max-width: 800px;
        max-height: 400px;
        width: 100%;
        object-fit: contain;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .sidebar {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        .sidebar.mobile-active {
            margin-left: 0;
        }

        .main-navbar {
            left: 0;
        }

        .main-content {
            margin-left: 0;
        }
    }

    /* Utility Classes */
    .cursor-pointer {
        cursor: pointer;
    }
</style>

<nav class="main-navbar px-3 d-flex align-items-center">
    <button type="button" id="sidebarCollapse" class="btn btn-light">
        <i class="fas fa-bars"></i>
    </button>

    <div class="ms-auto d-flex align-items-center">
        <div class="dropdown">
            <button class="btn btn-light me-3 position-relative" id="notifButton" data-bs-toggle="dropdown">
                <i class="fas fa-bell"></i>
                @if (auth()->user()->role == 2)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        id="notifCount" style="display: none;">
                        <span class="count-number">0</span>
                        <span class="visually-hidden">unread messages</span>
                    </span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end" id="notifDropdown">
                <div id="notifContent">
                    <!-- Notifications will be loaded here -->
                </div>
            </ul>
        </div>

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
@push('scripts')
    <script>
        $(document).ready(function() {
            const userRole = {{ Auth::user()->role }};
            function updateNotifications() {
                $.ajax({
                    url: '{{ route('notifications') }}',
                    method: 'GET',
                    success: function(response) {
                        let notifContent = $('#notifContent');
                        notifContent.empty();

                        // Update notification count
                        $('.count-number').text(response.count);

                        // Hide badge if no notifications
                        if (response.count == 0) {
                            $('#notifCount').hide();
                        } else {
                            $('#notifCount').show();
                        }

                        if (response.notifications.length > 0) {
                            response.notifications.forEach(function(notif) {
                                notifContent.append(`
    <li>
        <form action="/notifications/${notif.id}/update-status" method="POST" style="display:inline;" onclick="this.submit()">
            @csrf
            @method('PUT')
            <div class="dropdown-item ${notif.status == 1 ? 'notif-unread' : 'notif-read'}" style="cursor: pointer;">
                <div class="d-flex d-flex gap-2 col text-center">
                    ${userRole == 2 && notif.status == 1 ? '<span style="color: red; font-size: 12px; margin-right: 5px;">●</span>' : ''}
                    ${userRole == 2 && notif.status == 2 ? '<span style="color: white; font-size: 12px; margin-right: 5px;">●</span>' : ''}
                    <span>Pasien akan tiba: ${notif.desc}</span>
                </div>
            </div>
        </form>
    </li>
`);

                            });
                        } else {
                            notifContent.append(`
                            <li><span class="dropdown-item text-muted">No notifications</span></li>
                        `);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error fetching notifications:', xhr);
                        $('#notifContent').html(`
                        <li><span class="dropdown-item text-muted">Failed to load notifications</span></li>
                    `);
                    }
                });
            }

            // Update notifications when clicking the button
            $('#notifButton').on('click', function() {
                updateNotifications();
            });

            // Initial load of notification count
            updateNotifications();
        });
    </script>
@endpush

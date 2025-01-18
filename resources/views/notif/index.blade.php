{{-- hospital/index.blade.php --}}
@extends('app')

@section('title', 'Notif')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Notifications</h2>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Desc</th>
                                <th>Hospital</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($notif as $notif)
                                <tr>
                                    <td>{{ $notif->desc }}</td>
                                    <td>{{ $notif->hospital->name }}</td>
                                    <td>{{ $notif->status == 1 ? 'Unread' : 'Read'}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
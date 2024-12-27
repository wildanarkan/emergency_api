@extends('app')

@section('title', 'Users')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Users</h2>
        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Add New User</a>
        
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Hospital</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @switch($user->usertype)
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
                                    </td>
                                    <td>
                                        @if($user->hospital)
                                            {{ $user->hospital->name }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
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
{{-- user/index.blade.php --}}
@extends('app')

@section('title', 'Users')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Users</h2>
        <a href="{{ route('user.create') }}" class="btn btn-primary mb-3">Add New Nurse</a>
        
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Hospital</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $userData)
                                <tr>
                                    <td>{{ $userData->name }}</td>
                                    <td>{{ $userData->email }}</td>
                                    <td>{{ $userData->phone }}</td>
                                    <td>
                                        @switch($userData->role)
                                            @case(1)
                                                System Admin
                                                @break
                                            @case(2)
                                                Hospital Admin
                                                @break
                                            @case(3)
                                                Nurse
                                                @break
                                            @default
                                                Unknown Role
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($userData->role == 2) {{-- Jika user adalah Hospital Admin --}}
                                            @php
                                                $userHospital = $hospitals->where('user_id', $userData->id)->first();
                                            @endphp
                                            {{ $userHospital ? $userHospital->name : '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    {{-- <td>
                                        <a href="{{ route('user.edit', $userData->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('user.destroy', $userData->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                        </form>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No users found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
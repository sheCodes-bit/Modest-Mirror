@extends('layouts.admin')

@section('title', 'Users Management - ModestMirror')
@section('page_title', 'User Directory')

@section('content')
<!-- Feedback Alerts -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 mb-4 p-3 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger border-0 rounded-4 mb-4">
        <ul class="mb-0 list-unstyled">
            @foreach ($errors->all() as $error)
                <li><i class="fa-solid fa-circle-exclamation me-2"></i>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                <tr>
                    <th class="p-3 border-0">User ID</th>
                    <th class="p-3 border-0">Name</th>
                    <th class="p-3 border-0">Email</th>
                    <th class="p-3 border-0">Role</th>
                    <th class="p-3 border-0 text-center">Orders Placed</th>
                    <th class="p-3 border-0">Date Registered</th>
                    <th class="p-3 border-0 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="p-3 text-muted">#{{ $user->id }}</td>
                        <td class="p-3"><strong>{{ $user->name }}</strong></td>
                        <td class="p-3 text-muted">{{ $user->email }}</td>
                        <td class="p-3">
                            @if($user->isAdmin())
                                <span class="badge bg-warning text-dark px-3 py-1 text-uppercase">Admin</span>
                            @else
                                <span class="badge bg-secondary px-3 py-1 text-uppercase">Customer</span>
                            @endif
                        </td>
                        <td class="p-3 text-center fw-semibold">{{ $user->orders_count }}</td>
                        <td class="p-3 text-muted small">{{ $user->created_at->format('M d, Y H:i') }}</td>
                        <td class="p-3 text-end">
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user profile? All order logs will be lost!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger py-1 px-3">Delete Profile</button>
                                </form>
                            @else
                                <span class="text-muted small italic">Active Admin Profile</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

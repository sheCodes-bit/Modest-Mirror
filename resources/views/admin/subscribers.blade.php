@extends('layouts.admin')

@section('title', 'Newsletter Subscribers - ModestMirror')
@section('page_title', 'Newsletter Subscribers')

@section('content')
<!-- Feedback Alerts -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 mb-4 p-3 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                <tr>
                    <th class="p-3 border-0">Subscriber ID</th>
                    <th class="p-3 border-0">Email Address</th>
                    <th class="p-3 border-0">Subscription Date</th>
                    <th class="p-3 border-0 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $subscriber)
                    <tr>
                        <td class="p-3 text-muted">#{{ $subscriber->id }}</td>
                        <td class="p-3"><strong>{{ $subscriber->email }}</strong></td>
                        <td class="p-3 text-muted small">{{ $subscriber->created_at->format('M d, Y H:i') }}</td>
                        <td class="p-3 text-end">
                            <form action="{{ route('admin.subscribers.delete', $subscriber->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this subscriber from the list?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger py-1 px-3">Remove Subscriber</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-5 text-center text-muted">No newsletter subscribers yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

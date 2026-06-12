@extends('layouts.admin')

@section('title', 'Reviews Moderation - ModestMirror')
@section('page_title', 'Reviews Moderation')

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
                    <th class="p-3 border-0">Review ID</th>
                    <th class="p-3 border-0">Customer</th>
                    <th class="p-3 border-0">Product</th>
                    <th class="p-3 border-0">Rating</th>
                    <th class="p-3 border-0">Comment</th>
                    <th class="p-3 border-0">Date Added</th>
                    <th class="p-3 border-0 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td class="p-3 text-muted">#{{ $review->id }}</td>
                        <td class="p-3"><strong>{{ $review->user->name }}</strong><br><span class="text-muted small">{{ $review->user->email }}</span></td>
                        <td class="p-3">
                            <a href="{{ route('shop.show', $review->product->slug) }}" target="_blank" class="text-decoration-none text-dark fw-semibold">
                                {{ $review->product->name }}
                            </a>
                        </td>
                        <td class="p-3">
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fa-solid fa-star"></i>
                                    @else
                                        <i class="fa-regular fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </td>
                        <td class="p-3 text-muted" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $review->comment }}">
                            {{ $review->comment }}
                        </td>
                        <td class="p-3 text-muted small">{{ $review->created_at->format('M d, Y H:i') }}</td>
                        <td class="p-3 text-end">
                            <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review? This action is irreversible.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger py-1 px-3">Delete Review</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-5 text-center text-muted">No reviews have been written yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

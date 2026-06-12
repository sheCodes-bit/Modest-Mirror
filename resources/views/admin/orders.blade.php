@extends('layouts.admin')

@section('title', 'Orders Management - ModestMirror')
@section('page_title', 'Sales Orders')

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
                    <th class="p-3 border-0">Order ID</th>
                    <th class="p-3 border-0">Customer Details</th>
                    <th class="p-3 border-0">Items Order Summary</th>
                    <th class="p-3 border-0">Shipping coordinates</th>
                    <th class="p-3 border-0">Total</th>
                    <th class="p-3 border-0 text-center" style="width: 200px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-bottom">
                        <!-- Order ID -->
                        <td class="p-3">
                            <strong>#{{ $order->order_number }}</strong>
                            <div class="text-muted small">{{ $order->created_at->format('M d, Y H:i') }}</div>
                        </td>

                        <!-- Customer Details -->
                        <td class="p-3">
                            <div class="fw-semibold">{{ $order->user->name }}</div>
                            <div class="text-muted small">{{ $order->user->email }}</div>
                        </td>

                        <!-- Items ordered -->
                        <td class="p-3">
                            <ul class="list-unstyled mb-0 small">
                                @foreach($order->items as $item)
                                    <li>{{ $item->product->name }} <span class="text-muted fw-semibold">x {{ $item->quantity }}</span></li>
                                @endforeach
                            </ul>
                        </td>

                        <!-- Shipping Coordinates -->
                        <td class="p-3 text-muted" style="font-size: 0.85rem; line-height: 1.4;">
                            <div><strong>Recipient:</strong> {{ $order->shipping_name }}</div>
                            <div><strong>Phone:</strong> {{ $order->shipping_phone }}</div>
                            <div><strong>Address:</strong> {{ $order->shipping_address }}, {{ $order->shipping_city }}</div>
                        </td>

                        <!-- Total amount -->
                        <td class="p-3 fw-bold" style="color: var(--primary-coffee);">
                            PKR {{ number_format($order->total_amount) }}
                        </td>

                        <!-- Status update form -->
                        <td class="p-3 text-center">
                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="d-flex gap-1 align-items-center justify-content-center">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select form-control-luxury py-1 px-2" style="font-size: 0.85rem; width: auto;" onchange="this.form.submit()">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-luxury-outline py-1 px-2" title="Force Save Status">
                                    <i class="fa fa-save" style="font-size: 0.75rem;"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">No customer orders placed.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

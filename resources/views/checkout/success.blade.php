@extends('layouts.app')

@section('title', 'Order Placed - ModestMirror')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center py-5">
        <div class="col-md-7">
            <div class="card border-0 shadow-lg p-5 rounded-5 bg-white text-center">
                <!-- Golden Success Icon -->
                <div class="mx-auto mb-4 rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; border: 2px solid var(--gold-accent);">
                    <i class="fa-solid fa-circle-check fs-1 text-gold"></i>
                </div>

                <span class="font-monospace text-uppercase text-gold" style="font-size: 0.85rem; letter-spacing: 2px; font-weight: 600;">Thank You</span>
                <h1 class="brand-font display-4 mt-2 mb-3">Order Confirmed</h1>
                <p class="text-muted lead mb-5">Your order has been logged in our luxury fulfillment system. A confirmation mail details your shipment schedule.</p>

                <!-- Receipt Detail Cards -->
                <div class="row g-3 mb-5 text-start">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-4 h-100">
                            <h6 class="font-monospace text-uppercase text-muted mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Receipt Coordinates</h6>
                            <p class="mb-1 text-dark"><strong>Order:</strong> #{{ $order->order_number }}</p>
                            <p class="mb-1 text-dark"><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                            <p class="mb-0 text-dark"><strong>Total amount:</strong> PKR {{ number_format($order->total_amount) }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-4 h-100">
                            <h6 class="font-monospace text-uppercase text-muted mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Shipment Coordinates</h6>
                            <p class="mb-1 text-dark"><strong>Recipient:</strong> {{ $order->shipping_name }}</p>
                            <p class="mb-1 text-dark"><strong>Phone:</strong> {{ $order->shipping_phone }}</p>
                            <p class="mb-0 text-dark"><strong>Address:</strong> {{ $order->shipping_address }}, {{ $order->shipping_city }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ordered Items -->
                <div class="text-start mb-5">
                    <h5 class="brand-font mb-3">Ordered Items</h5>
                    <div class="list-group list-group-flush rounded-4 overflow-hidden shadow-sm">
                        @foreach($order->items as $item)
                            <div class="list-group-item d-flex align-items-center justify-content-between p-3 border-0 bg-light mb-1">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-3 overflow-hidden border" style="width: 40px; height: 50px;">
                                        @if($item->product->primaryImage)
                                            <img src="{{ asset($item->product->primaryImage->image_path) }}" class="w-100 h-100" style="object-fit: cover;">
                                        @else
                                            <img src="https://placehold.co/100x120/E8DCCB/3B2F2F?text={{ urlencode($item->product->name) }}" class="w-100 h-100" style="object-fit: cover;">
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size: 0.9rem;">{{ $item->product->name }}</div>
                                        <div class="text-muted small">Qty: {{ $item->quantity }}</div>
                                    </div>
                                </div>
                                <div class="fw-semibold text-dark">
                                    PKR {{ number_format($item->price * $item->quantity) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                    <a href="{{ route('dashboard') }}" class="btn btn-luxury py-3 px-4"><i class="fa-regular fa-id-card me-2"></i>Go to my Dashboard</a>
                    <a href="{{ route('shop.index') }}" class="btn btn-luxury-outline py-3 px-4"><i class="fa-solid fa-bag-shopping me-2"></i>Keep Shopping</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

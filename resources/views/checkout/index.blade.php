@extends('layouts.app')

@section('title', 'Checkout - ModestMirror')

@section('content')
<div class="container py-5">
    <h1 class="brand-font display-4 mb-4">Checkout</h1>
    <hr class="mb-5" style="width: 60px; height: 3px; background-color: var(--gold-accent); border: 0; opacity: 1;">

    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-4 mb-4">
            <ul class="mb-0 list-unstyled">
                @foreach ($errors->all() as $error)
                    <li><i class="fa-solid fa-circle-exclamation me-2"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-5">
        <!-- Left Side: Shipping Address Form -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
                <h4 class="brand-font mb-4"><i class="fa-solid fa-truck-ramp-box me-2 text-gold"></i>Shipping Details</h4>
                
                <form action="{{ route('checkout.place-order') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="shipping_name" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Full Name</label>
                        <input type="text" class="form-control form-control-luxury" id="shipping_name" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" pattern="[A-Za-z ]+" minlength="3" required placeholder="Enter your full name">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="shipping_email" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Email Address</label>
                            <input type="email" class="form-control form-control-luxury" id="shipping_email" name="shipping_email" value="{{ old('shipping_email', auth()->user()->email) }}" required placeholder="Enter your email address">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="shipping_phone" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Phone Number</label>
                            <input type="text" class="form-control form-control-luxury" id="shipping_phone" name="shipping_phone" value="{{ old('shipping_phone') }}" pattern="03[0-9]{9}" required placeholder="03XX-XXXXXXX">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="shipping_address" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Street Address</label>
                        <input type="text" class="form-control form-control-luxury" id="shipping_address" name="shipping_address" value="{{ old('shipping_address') }}" required placeholder="House #123, Street 5, Near Main Bazar">
                    </div>

                    <div class="mb-4">
                        <label for="shipping_city" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">City</label>
                        <input type="text" class="form-control form-control-luxury" id="shipping_city" name="shipping_city" value="{{ old('shipping_city') }}" required placeholder="Burewala">
                    </div>

                    <div class="p-3 mb-4 rounded-4" style="background-color: var(--background-beige);">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="cod" checked disabled>
                            <label class="form-check-label text-dark fw-medium" for="cod">
                                Cash on Delivery (COD) / Premium Home Styling Delivery
                            </label>
                            <small class="text-muted d-block mt-1">Our customer concierge will bring the styled package directly to your door, allowing standard fitting confirmation.</small>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-luxury py-3 fs-6"><i class="fa-solid fa-circle-check me-2"></i>Place My Luxury Order</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Side: Order Summary -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4 rounded-4" style="background-color: var(--white);">
                <h4 class="brand-font mb-4">Order Summary</h4>
                
                <!-- Items list -->
                <div class="mb-4" style="max-height: 280px; overflow-y: auto;">
                    @foreach($cartItems as $item)
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 overflow-hidden border" style="width: 50px; height: 60px;">
                                    @if($item->product->primaryImage)
                                        <img src="{{ asset($item->product->primaryImage->image_path) }}" class="w-100 h-100" style="object-fit: cover;">
                                    @else
                                        <img src="https://placehold.co/100x120/E8DCCB/3B2F2F?text={{ urlencode($item->product->name) }}" class="w-100 h-100" style="object-fit: cover;">
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark" style="font-size: 0.95rem;">{{ $item->product->name }}</div>
                                    <div class="text-muted small">Qty: {{ $item->quantity }}</div>
                                </div>
                            </div>
                            <div class="fw-semibold text-dark">
                                PKR {{ number_format($item->product->price * $item->quantity) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <hr style="border-color: var(--light-sand);">

                <div class="d-flex justify-content-between mb-2 text-muted">
                    <span>Subtotal</span>
                    <span>PKR {{ number_format($subtotal) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 text-muted">
                    <span>Shipping</span>
                    <span class="text-success">Complimentary</span>
                </div>

                <hr style="border-color: var(--light-sand);">

                <div class="d-flex justify-content-between mb-0 fs-5">
                    <span class="brand-font">Total Amount</span>
                    <span class="fw-bold text-dark">PKR {{ number_format($subtotal) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

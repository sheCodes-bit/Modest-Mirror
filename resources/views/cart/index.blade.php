@extends('layouts.app')

@section('title', 'Shopping Bag - ModestMirror')

@section('content')
<div class="container py-5">
    <h1 class="brand-font display-4 mb-4">Your Shopping Bag</h1>
    <hr class="mb-5" style="width: 60px; height: 3px; background-color: var(--gold-accent); border: 0; opacity: 1;">

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

    @if($cartItems->isEmpty())
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="fa-solid fa-bag-shopping text-gold display-3 mb-3"></i>
            <h3 class="brand-font">Your bag is empty</h3>
            <p class="text-muted">You haven't added any luxury hijabs to your cart yet.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-luxury mt-3">Explore Catalog</a>
        </div>
    @else
        <div class="row g-5">
            <!-- Left Side: Table of Items -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                                <tr>
                                    <th class="p-3 border-0">Product</th>
                                    <th class="p-3 border-0">Price</th>
                                    <th class="p-3 border-0 text-center" style="width: 150px;">Quantity</th>
                                    <th class="p-3 border-0">Total</th>
                                    <th class="p-3 border-0"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr>
                                        <!-- Product Detail -->
                                        <td class="p-3 border-bottom-0">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-3 overflow-hidden border" style="width: 60px; height: 70px;">
                                                    @if($item->product->primaryImage)
                                                        <img src="{{ asset($item->product->primaryImage->image_path) }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $item->product->name }}">
                                                    @else
                                                        <img src="https://placehold.co/100x120/E8DCCB/3B2F2F?text={{ urlencode($item->product->name) }}" class="w-100 h-100" style="object-fit: cover;">
                                                    @endif
                                                </div>
                                                <div>
                                                    <a href="{{ route('shop.show', $item->product->slug) }}" class="fw-semibold text-dark text-decoration-none hover-gold-text">{{ $item->product->name }}</a>
                                                    <div class="text-muted small">{{ $item->product->fabric_type }} | {{ $item->product->color }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- Price -->
                                        <td class="p-3 border-bottom-0 text-muted">
                                            PKR {{ number_format($item->product->price) }}
                                        </td>

                                        <!-- Quantity Update Form -->
                                        <td class="p-3 border-bottom-0 text-center">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center justify-content-center gap-1">
                                                @csrf
                                                @method('PUT')
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="10" class="form-control form-control-luxury text-center py-1 px-2" style="width: 65px; font-size: 0.9rem;">
                                                <button type="submit" class="btn btn-sm btn-luxury-outline px-2 py-1" title="Update Quantity">
                                                    <i class="fa fa-sync-alt" style="font-size: 0.75rem;"></i>
                                                </button>
                                            </form>
                                        </td>

                                        <!-- Total -->
                                        <td class="p-3 border-bottom-0 fw-semibold">
                                            PKR {{ number_format($item->product->price * $item->quantity) }}
                                        </td>

                                        <!-- Remove Item Form -->
                                        <td class="p-3 border-bottom-0 text-end">
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('Remove this hijab from your bag?')" title="Remove Item">
                                                    <i class="fa-regular fa-trash-can fs-5"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Side: Order Summary Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 rounded-4" style="background-color: var(--white);">
                    <h4 class="brand-font mb-4">Summary</h4>
                    
                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Subtotal</span>
                        <span class="text-dark fw-semibold">PKR {{ number_format($subtotal) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Shipping</span>
                        <span class="text-success fw-medium">Complimentary</span>
                    </div>
                    
                    <hr style="border-color: var(--light-sand);">

                    <div class="d-flex justify-content-between mb-4 fs-5">
                        <span class="brand-font">Total</span>
                        <span class="fw-bold" style="color: var(--primary-coffee);">PKR {{ number_format($subtotal) }}</span>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('checkout.index') }}" class="btn btn-luxury py-3"><i class="fa-solid fa-credit-card me-2"></i>Proceed to Checkout</a>
                        <a href="{{ route('shop.index') }}" class="btn btn-luxury-outline py-3"><i class="fa-solid fa-arrow-left me-2"></i>Continue Shopping</a>
                    </div>

                    <div class="text-center mt-3 small text-muted">
                        <i class="fa fa-lock me-1"></i> Secure checkout powered by ModestMirror
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

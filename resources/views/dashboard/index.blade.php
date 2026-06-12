@extends('layouts.app')

@section('title', 'My Dashboard - ModestMirror')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="font-monospace text-uppercase text-gold" style="font-size: 0.85rem; letter-spacing: 2px; font-weight: 600;">Personal Studio</span>
            <h1 class="brand-font display-4 mt-2">Salaam, {{ $user->name }}</h1>
        </div>
        <div>
            <span class="badge badge-gold px-3 py-2 text-uppercase font-monospace" style="letter-spacing: 1px;">Customer Account</span>
        </div>
    </div>
    
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

    <!-- Dashboard Tabs -->
    <ul class="nav nav-tabs border-bottom mb-4" id="dashboardTabs" role="tablist" style="border-color: var(--light-sand) !important;">
        <li class="nav-item" role="presentation">
            <button class="nav-link active border-0 py-3 brand-font fs-5 text-dark" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders-pane" type="button" role="tab" aria-controls="orders-pane" aria-selected="true" style="background: transparent;">
                <i class="fa-solid fa-bag-shopping text-gold me-2"></i>Order History
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link border-0 py-3 brand-font fs-5 text-dark" id="tryons-tab" data-bs-toggle="tab" data-bs-target="#tryons-pane" type="button" role="tab" aria-controls="tryons-pane" aria-selected="false" style="background: transparent;">
                <i class="fa-solid fa-camera text-gold me-2"></i>My Saved Try-Ons
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link border-0 py-3 brand-font fs-5 text-dark" id="wishlist-tab" data-bs-toggle="tab" data-bs-target="#wishlist-pane" type="button" role="tab" aria-controls="wishlist-pane" aria-selected="false" style="background: transparent;">
                <i class="fa-solid fa-heart text-gold me-2"></i>Saved Items
            </button>
        </li>
    </ul>

    <div class="tab-content bg-white p-4 rounded-4 shadow-sm" id="dashboardTabsContent">
        <!-- 1. Orders Pane -->
        <div class="tab-pane fade show active" id="orders-pane" role="tabpanel" aria-labelledby="orders-tab" tabindex="0">
            @if($orders->isEmpty())
                <div class="text-center py-5">
                    <i class="fa-solid fa-bag-shopping text-gold display-4 mb-3"></i>
                    <h5 class="brand-font">No Orders Placed Yet</h5>
                    <p class="text-muted">You haven't ordered any luxury products yet.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-luxury btn-sm mt-2">Explore Shop</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="bg-light font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                            <tr>
                                <th class="p-3 border-0">Order Number</th>
                                <th class="p-3 border-0">Date</th>
                                <th class="p-3 border-0">Items</th>
                                <th class="p-3 border-0">Total</th>
                                <th class="p-3 border-0 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="p-3"><strong>#{{ $order->order_number }}</strong></td>
                                    <td class="p-3 text-muted">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="p-3">
                                        <ul class="list-unstyled mb-0 small">
                                            @foreach($order->items as $item)
                                                <li>{{ $item->product->name }} <span class="text-muted">x {{ $item->quantity }}</span></li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="p-3 fw-semibold">PKR {{ number_format($order->total_amount) }}</td>
                                    <td class="p-3 text-center">
                                        @if($order->status === 'completed')
                                            <span class="badge bg-success px-3 py-2 text-uppercase">Completed</span>
                                        @elseif($order->status === 'shipped')
                                            <span class="badge bg-primary px-3 py-2 text-uppercase">Shipped</span>
                                        @elseif($order->status === 'processing')
                                            <span class="badge bg-warning text-dark px-3 py-2 text-uppercase">Processing</span>
                                        @elseif($order->status === 'cancelled')
                                            <span class="badge bg-danger px-3 py-2 text-uppercase">Cancelled</span>
                                        @else
                                            <span class="badge bg-secondary px-3 py-2 text-uppercase">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- 2. Saved Try-Ons Pane -->
        <div class="tab-pane fade" id="tryons-pane" role="tabpanel" aria-labelledby="tryons-tab" tabindex="0">
            @if(empty($screenshots))
                <div class="text-center py-5">
                    <i class="fa-solid fa-camera-retro text-gold display-4 mb-3"></i>
                    <h5 class="brand-font">No Saved Try-On Snapshots</h5>
                    <p class="text-muted">Launch the AR try-on camera, try on a hijab, and snap a picture to see it here!</p>
                    <a href="{{ route('ar.tryon') }}" class="btn btn-luxury btn-sm mt-2"><i class="fa-solid fa-camera me-1"></i>Open Camera Room</a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($screenshots as $screenshot)
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm overflow-hidden h-100 bg-light rounded-4">
                                <div class="position-relative" style="padding-bottom: 75%; overflow: hidden;">
                                    <img src="{{ asset($screenshot) }}" class="position-absolute top-0 left-0 w-100 h-100" style="object-fit: cover;" alt="Try on snapshot">
                                </div>
                                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                    <a href="{{ asset($screenshot) }}" download="modestmirror_snapshot.png" class="btn btn-sm btn-luxury-outline py-1 px-3">
                                        <i class="fa fa-download me-1"></i>Download
                                    </a>
                                    <form action="{{ route('dashboard.delete-screenshot') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="filename" value="{{ basename($screenshot) }}">
                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0" onclick="return confirm('Delete this snapshot from dashboard?')">
                                            <i class="fa-regular fa-trash-can fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- 3. Saved Items (Wishlist) Pane -->
        <div class="tab-pane fade" id="wishlist-pane" role="tabpanel" aria-labelledby="wishlist-tab" tabindex="0">
            @if($wishlistItems->isEmpty())
                <div class="text-center py-5">
                    <i class="fa-regular fa-heart text-gold display-4 mb-3"></i>
                    <h5 class="brand-font">No Saved Products</h5>
                    <p class="text-muted">Explore our shop and tap the heart icon on any hijab details page.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-luxury btn-sm mt-2">Explore Shop</a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($wishlistItems as $wItem)
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card border h-100 rounded-4 overflow-hidden shadow-sm">
                                <div style="padding-bottom: 110%; overflow: hidden; position: relative;">
                                    @if($wItem->product->primaryImage)
                                        <img src="{{ asset($wItem->product->primaryImage->image_path) }}" class="position-absolute top-0 left-0 w-100 h-100" style="object-fit: cover;">
                                    @else
                                        <img src="https://placehold.co/400x500/E8DCCB/3B2F2F?text={{ urlencode($wItem->product->name) }}" class="position-absolute top-0 left-0 w-100 h-100" style="object-fit: cover;">
                                    @endif
                                </div>
                                <div class="card-body p-3 d-flex flex-column">
                                    <h6 class="fw-semibold text-dark mb-1">{{ $wItem->product->name }}</h6>
                                    <p class="text-muted mb-2 small">PKR {{ number_format($wItem->product->price) }}</p>
                                    <div class="d-flex gap-2 mt-auto">
                                        <a href="{{ route('shop.show', $wItem->product->slug) }}" class="btn btn-sm btn-luxury w-100">Details</a>
                                        @if($wItem->product->ar_overlay_path)
                                            <a href="{{ route('ar.tryon', ['product' => $wItem->product->id]) }}" class="btn btn-sm btn-luxury-outline w-100"><i class="fa-solid fa-camera"></i> AR</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

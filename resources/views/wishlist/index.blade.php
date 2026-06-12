@extends('layouts.app')

@section('title', 'My Wishlist - ModestMirror')

@section('content')
<div class="container py-5">
    <h1 class="brand-font display-4 mb-4">Saved Wishlist</h1>
    <hr class="mb-5" style="width: 60px; height: 3px; background-color: var(--gold-accent); border: 0; opacity: 1;">

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 mb-4 p-3 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($wishlistItems->isEmpty())
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="fa-regular fa-heart text-gold display-3 mb-3"></i>
            <h3 class="brand-font">Your wishlist is empty</h3>
            <p class="text-muted">Save your favorite luxury hijabs to monitor colors or try on in AR later.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-luxury mt-3">Explore Catalog</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($wishlistItems as $item)
                <div class="col-md-6 col-lg-3">
                    <div class="product-card position-relative">
                        <!-- Remove button floating top-right -->
                        <div class="position-absolute top-0 end-0 p-3" style="z-index: 5;">
                            <form action="{{ route('wishlist.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-light bg-white rounded-circle p-2 shadow-sm" style="width: 40px; height: 40px; border: 0;" title="Remove from Wishlist">
                                    <i class="fa-solid fa-xmark text-danger"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Product Image -->
                        <div class="product-card-img-wrapper">
                            @if($item->product->primaryImage)
                                <img src="{{ asset($item->product->primaryImage->image_path) }}" class="product-card-img" alt="{{ $item->product->name }}">
                            @else
                                <img src="https://placehold.co/400x500/E8DCCB/3B2F2F?text={{ urlencode($item->product->name) }}" class="product-card-img" alt="{{ $item->product->name }}">
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="product-card-body">
                            <span class="product-card-category">{{ $item->product->category->name }}</span>
                            <a href="{{ route('shop.show', $item->product->slug) }}" class="product-card-title">{{ $item->product->name }}</a>
                            <div class="product-card-price">PKR {{ number_format($item->product->price) }}</div>
                            
                            <!-- Move to Cart Form -->
                            <form action="{{ route('wishlist.move-to-cart', $item->id) }}" method="POST" class="mt-auto">
                                @csrf
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-luxury py-2"><i class="fa fa-shopping-bag me-1"></i> Move to Bag</button>
                                    @if($item->product->ar_overlay_path)
                                        <a href="{{ route('ar.tryon', ['product' => $item->product->id]) }}" class="btn btn-luxury-outline py-2"><i class="fa-solid fa-camera me-1"></i> AR Try-On</a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

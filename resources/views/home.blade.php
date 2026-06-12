@extends('layouts.app')

@section('title', 'ModestMirror - Premium AR Hijab Try-On & Luxury E-Commerce')

@section('content')
<!-- Hero Carousel Section -->
<div class="container mt-4">
    <div id="heroCarousel" class="carousel slide carousel-fade hero-slider" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to-0 class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to-1 aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to-2 aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner" style="border-radius: 20px;">
            <div class="carousel-item active">
                <div class="hero-slider-item d-flex align-items-center" style="background-image: linear-gradient(rgba(30, 24, 24, 0.65), rgba(30, 24, 24, 0.35)), url('{{ asset('images/slider/slide1.jpg') }}');">
                    <div class="container px-5 text-white">
                        <div class="row">
                            <div class="col-lg-7">
                                <span class="badge badge-gold text-uppercase mb-3 px-3 py-2" style="letter-spacing: 2px; font-size: 0.8rem;">Exclusive Technology</span>
                                <h1 class="display-3 brand-font text-white mb-3" style="line-height: 1.2;">Where Modesty Meets Technology</h1>
                                <p class="lead mb-4 text-white-50">Experience the world's first luxury AR Virtual Hijab Try-On. Real-time face tracking for natural drape simulations.</p>
                                <div class="d-flex gap-3">
                                    <a href="{{ route('ar.tryon') }}" class="btn btn-gold py-3 px-4"><i class="fa-solid fa-camera me-2"></i>Launch AR Try-On</a>
                                    <a href="{{ route('shop.index') }}" class="btn btn-luxury-outline text-white border-white py-3 px-4">Explore Shop</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="hero-slider-item d-flex align-items-center" style="background-image: linear-gradient(rgba(30, 24, 24, 0.65), rgba(30, 24, 24, 0.35)), url('{{ asset('images/slider/slide2.jpg') }}');">
                    <div class="container px-5 text-white">
                        <div class="row">
                            <div class="col-lg-7">
                                <span class="badge badge-gold text-uppercase mb-3 px-3 py-2" style="letter-spacing: 2px; font-size: 0.8rem;">Summer Collection 2026</span>
                                <h1 class="display-3 brand-font text-white mb-3" style="line-height: 1.2;">Premium Fabrics, Perfect Cut</h1>
                                <p class="lead mb-4 text-white-50">Indulge in organic Egyptian cotton, featherlight silk chiffon, and rich royal satins crafted for modern elegance.</p>
                                <div class="d-flex gap-3">
                                    <a href="{{ route('shop.index') }}" class="btn btn-gold py-3 px-4">Shop Collection</a>
                                    <a href="#about" class="btn btn-luxury-outline text-white border-white py-3 px-4">Our Story</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="hero-slider-item d-flex align-items-center" style="background-image: linear-gradient(rgba(30, 24, 24, 0.65), rgba(30, 24, 24, 0.35)), url('{{ asset('images/slider/slide3.jpg') }}');">
                    <div class="container px-5 text-white">
                        <div class="row">
                            <div class="col-lg-7">
                                <span class="badge badge-gold text-uppercase mb-3 px-3 py-2" style="letter-spacing: 2px; font-size: 0.8rem;">Elegant Design</span>
                                <h1 class="display-3 brand-font text-white mb-3" style="line-height: 1.2;">Try on Virtually, Feel Personally</h1>
                                <p class="lead mb-4 text-white-50">Find your ideal tone and drape from the comfort of your home. Save snapshots directly to your custom dashboard.</p>
                                <div class="d-flex gap-3">
                                    <a href="{{ route('ar.tryon') }}" class="btn btn-gold py-3 px-4"><i class="fa-solid fa-smile me-2"></i>Start Try-On</a>
                                    <a href="{{ route('register') }}" class="btn btn-luxury-outline text-white border-white py-3 px-4">Register Account</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<!-- Category Categories Section -->
<section class="py-5 mt-5" id="categories">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="font-monospace text-uppercase text-gold" style="font-size: 0.85rem; letter-spacing: 2px; font-weight: 600;">Curated Collections</span>
            <h2 class="brand-font display-5 mt-2">Shop By Category</h2>
            <hr class="mx-auto" style="width: 60px; height: 3px; background-color: var(--gold-accent); border: 0; opacity: 1;">
        </div>
        @php
                          $categoryImages = [
                          'casual-elegance' => 'casual.jpg',
                          'formal-chic' => 'formal.jpg',
                          'bridal-luxury' => 'bridal.jpg',
                          'everyday-basics' => 'everyday.jpg',
                         ];
                        @endphp
        <div class="row g-4">
            @foreach($categories as $category)
                <div class="col-md-6 col-lg-3">
                    <div class="category-card" onclick="window.location.href='{{ route('shop.index', ['category' => $category->slug]) }}'">
                        <!-- Automatically maps a placeholder image based on category ID or similar if no image exists -->
                        <img src="{{ asset('images/categories/' . ($categoryImages[$category->slug] ?? 'default.png')) }}" alt="{{ $category->name }}">
                        <div class="category-card-overlay">
                            <h3 class="category-card-title">{{ $category->name }}</h3>
                            <span class="category-card-subtitle">Discover Items <i class="fa fa-arrow-right ms-1" style="font-size: 0.8rem;"></i></span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- AR Try-On Promo Section -->
<section class="py-5">
    <div class="container py-3">
        <div class="promotion-banner shadow-lg text-center text-lg-start">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <span class="badge bg-gold text-light text-uppercase px-3 py-2 mb-3" style="font-size: 0.75rem; letter-spacing: 1px; font-weight: 600;">Real-Time AR Mirror</span>
                    <h2 class="brand-font display-4 text-white mb-3">Experience our Luxury Virtual Fitting Room</h2>
                    <p class="lead text-white-50 mb-4">No permissions issues, no shipping doubts. Our webcam-activated virtual try-on automatically fits, scales, and aligns luxury hijabs onto your face landmarks in real-time. Snap and share your coordinates.</p>
                    <a href="{{ route('ar.tryon') }}" class="btn btn-gold py-3 px-4"><i class="fa-solid fa-video me-2"></i>Enter AR Try-On Room</a>
                </div>
                <div class="col-lg-5 text-center">
                    <div class="position-relative d-inline-block p-3 bg-white rounded-5 shadow-lg shadow-dark" style="border: 2px solid var(--gold-accent);">
                        <img src="{{ asset('images/about/about3.jpg') }}" class="img-fluid rounded-4" alt="Virtual Try On Preview" style="max-height: 320px; object-fit: cover;">
                        <span class="position-absolute top-50 start-50 translate-middle btn btn-light rounded-circle p-3 shadow-lg" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-camera text-gold fs-4"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-5">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="font-monospace text-uppercase text-gold" style="font-size: 0.85rem; letter-spacing: 2px; font-weight: 600;">Our Signature Pieces</span>
            <h2 class="brand-font display-5 mt-2">Featured Collection</h2>
            <hr class="mx-auto" style="width: 60px; height: 3px; background-color: var(--gold-accent); border: 0; opacity: 1;">
        </div>

        <div class="row g-4">
            @forelse($featuredProducts as $product)
                <div class="col-md-6 col-lg-3">
                    <div class="product-card">
                        <div class="product-card-img-wrapper">
                            @if($product->primaryImage)
                                <img src="{{ asset($product->primaryImage->image_path) }}" class="product-card-img" alt="{{ $product->name }}" onError="this.src='https://placehold.co/400x500/E8DCCB/3B2F2F?text={{ urlencode($product->name) }}'">
                            @else
                                <img src="https://placehold.co/400x500/E8DCCB/3B2F2F?text={{ urlencode($product->name) }}" class="product-card-img" alt="{{ $product->name }}">
                            @endif
                        </div>
                        <div class="product-card-body">
                            <span class="product-card-category">{{ $product->category->name }}</span>
                            <a href="{{ route('shop.show', $product->slug) }}" class="product-card-title">{{ $product->name }}</a>
                            <div class="product-card-price">PKR {{ number_format($product->price) }}</div>
                            <div class="product-card-actions">
                                @if($product->ar_overlay_path)
                                    <a href="{{ route('ar.tryon', ['product' => $product->id]) }}" class="btn btn-luxury-outline"><i class="fa-solid fa-camera me-1"></i>AR Try-on</a>
                                @else
                                    <button class="btn btn-luxury-outline" disabled><i class="fa-solid fa-ban me-1"></i>No AR</button>
                                @endif
                                <a href="{{ route('shop.show', $product->slug) }}" class="btn btn-luxury">Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted">No products found. Please run the seeder: <code>php artisan db:seed</code></p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Brand Story (About) Section -->
<section class="py-5 bg-white" id="about">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="font-monospace text-uppercase text-gold" style="font-size: 0.85rem; letter-spacing: 2px; font-weight: 600;">Since 2026</span>
                <h2 class="brand-font display-4 mt-2">Redefining Modest Luxury</h2>
                <hr class="mb-4" style="width: 60px; height: 3px; background-color: var(--gold-accent); border: 0; opacity: 1;">
                <p class="mb-3 lead" style="color: var(--mocha-brown);">ModestMirror bridges the gap between high-fashion modest styling and forward-looking web technology.</p>
                <p class="text-muted mb-4">Every piece is handpicked and measured to deliver pristine finishes, utilizing materials ranging from Turkish silks to Italian-woven cotton jerseys. Through our Real-Time AR Mirror, you can try on our complete line, matching tones and draping flows, making custom selection intuitive, private, and simple.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-luxury py-3 px-4">Visit our Catalog</a>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <img src="{{ asset('images/about/about1.jpg') }}" class="img-fluid rounded-4 shadow-sm" alt="Luxury Modest Fashion" style="height: 380px; width: 100%; object-fit: cover;">
                    </div>
                    <div class="col-6 mt-4">
                        <img src="{{ asset('images/about/about2.jpg') }}" class="img-fluid rounded-4 shadow-sm" alt="Luxury Fabric Detail" style="height: 380px; width: 100%; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

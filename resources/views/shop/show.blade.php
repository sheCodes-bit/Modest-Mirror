@extends('layouts.app')

@section('title', $product->name . ' - ModestMirror')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-muted text-decoration-none">Shop</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="text-muted text-decoration-none">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active text-dark" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 mb-4 p-3 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-5">
        <!-- Left Column: Image Gallery/Carousel -->
        <div class="col-lg-6">
            <div class="card border-0 bg-white p-2 rounded-4 shadow-sm">
                <div id="productGallery" class="carousel slide" data-bs-ride="false">
                    <div class="carousel-inner rounded-4" style="background-color: var(--background-beige);">
                        @forelse($product->images as $index => $img)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <img src="{{ asset($img->image_path) }}" class="d-block w-100 img-fluid rounded-4" style="max-height: 580px; object-fit: cover;" alt="Hijab view" onError="this.src='https://placehold.co/600x680/E8DCCB/3B2F2F?text={{ urlencode($product->name) }}'">
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <img src="https://placehold.co/600x680/E8DCCB/3B2F2F?text={{ urlencode($product->name) }}" class="d-block w-100 img-fluid rounded-4" alt="Placeholder">
                            </div>
                        @endforelse
                    </div>
                    @if($product->images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#productGallery" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(1);"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productGallery" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(1);"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Thumbnail Selector -->
            @if($product->images->count() > 1)
                <div class="d-flex gap-2 mt-3 justify-content-center">
                    @foreach($product->images as $index => $img)
                        <button type="button" data-bs-target="#productGallery" data-bs-slide-to="{{ $index }}" class="btn p-0 border rounded-3 overflow-hidden shadow-sm" style="width: 70px; height: 80px;">
                            <img src="{{ asset($img->image_path) }}" class="w-100 h-100" style="object-fit: cover;">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="col-lg-6">
            <span class="font-monospace text-uppercase text-gold" style="font-size: 0.85rem; letter-spacing: 2px; font-weight: 600;">{{ $product->category->name }}</span>
            <h1 class="brand-font display-4 mt-2 mb-1">{{ $product->name }}</h1>
            
            <!-- Ratings Summary -->
            @php
                $avgRating = $product->reviews->avg('rating');
                $reviewsCount = $product->reviews->count();
            @endphp
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="text-warning" style="font-size: 0.9rem;">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($avgRating))
                            <i class="fa-solid fa-star"></i>
                        @else
                            <i class="fa-regular fa-star"></i>
                        @endif
                    @endfor
                </div>
                <span class="text-muted small">
                    @if($reviewsCount > 0)
                        {{ number_format($avgRating, 1) }} ({{ $reviewsCount }} {{ Str::plural('review', $reviewsCount) }})
                    @else
                        No reviews yet
                    @endif
                </span>
            </div>
            
            <div class="fs-3 fw-bold text-dark mb-4">
                PKR {{ number_format($product->price) }}
            </div>

            <hr style="border-color: var(--light-sand); opacity: 0.7;">

            <!-- Product Specs Table -->
            <div class="my-4">
                <table class="table table-borderless fs-6" style="max-width: 400px;">
                    <tbody>
                        <tr>
                            <td class="font-monospace text-uppercase text-muted p-1" style="font-size: 0.75rem; letter-spacing: 1px; width: 140px;">Fabric Material:</td>
                            <td class="text-dark p-1 fw-medium">{{ $product->fabric_type }}</td>
                        </tr>
                        <tr>
                            <td class="font-monospace text-uppercase text-muted p-1" style="font-size: 0.75rem; letter-spacing: 1px;">Color Palette:</td>
                            <td class="text-dark p-1 fw-medium">{{ $product->color }}</td>
                        </tr>
                        <tr>
                            <td class="font-monospace text-uppercase text-muted p-1" style="font-size: 0.75rem; letter-spacing: 1px;">Fitting Options:</td>
                            <td class="p-1 fw-medium">
                                @if($product->ar_overlay_path)
                                    <span class="badge bg-success text-white px-2 py-1"><i class="fa fa-video me-1"></i> AR Mirror Ready</span>
                                @else
                                    <span class="badge bg-secondary text-white px-2 py-1">Standard Fitting</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <h5 class="brand-font mb-2">Description</h5>
                <p class="text-muted" style="line-height: 1.6;">{{ $product->description }}</p>
            </div>

            <hr style="border-color: var(--light-sand); opacity: 0.7;">

            <!-- AR Room Quick Activation -->
            @if($product->ar_overlay_path)
                <div class="p-3 mb-4 rounded-4 shadow-sm text-center" style="background: linear-gradient(135deg, rgba(200, 169, 106, 0.1) 0%, rgba(232, 220, 203, 0.2) 100%); border: 1px solid rgba(200, 169, 106, 0.3);">
                    <h6 class="brand-font mb-1"><i class="fa-solid fa-camera text-gold me-2"></i>Virtual Fit Check</h6>
                    <p class="text-muted small mb-2">Unsure about color matching? Open our real-time AR camera mirror to test on your face.</p>
                    <a href="{{ route('ar.tryon', ['product' => $product->id]) }}" class="btn btn-gold btn-sm px-4 text-uppercase font-monospace" style="font-size: 0.75rem; letter-spacing: 1px;">Launch Camera Room</a>
                </div>
            @endif

            <!-- Purchase Controls -->
            <div class="mt-4">
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <div class="row g-3 align-items-center mb-4">
                        <div class="col-auto">
                            <label for="quantity" class="font-monospace text-uppercase text-muted small" style="letter-spacing: 1px;">Quantity:</label>
                        </div>
                        <div class="col-3">
                            <input type="number" class="form-control form-control-luxury py-2 text-center" id="quantity" name="quantity" value="1" min="1" max="10">
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-luxury w-100 py-3"><i class="fa fa-shopping-bag me-2"></i>Add to Shopping Bag</button>
                        </div>
                    </div>
                </form>

                <!-- Wishlist Interaction -->
                <form action="{{ route('wishlist.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-luxury-outline w-100 py-3"><i class="fa-regular fa-heart me-2"></i>Add to Saved Wishlist</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-5 pt-5 border-top" style="border-color: var(--light-sand) !important;">
        <div class="row g-5">
            <!-- Left Side: Display Reviews -->
            <div class="col-lg-7">
                <h3 class="brand-font mb-4">Customer Reviews</h3>
                @forelse($product->reviews->sortByDesc('created_at') as $review)
                    <div class="review-card mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $review->user->name }}</h6>
                                <div class="text-warning small">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fa-solid fa-star"></i>
                                        @else
                                            <i class="fa-regular fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <span class="text-muted small">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mb-0 text-muted" style="font-size: 0.9rem; line-height: 1.5;">{{ $review->comment }}</p>
                        
                        @if(auth()->check() && (auth()->id() === $review->user_id || auth()->user()->isAdmin()))
                            <div class="d-flex gap-2 justify-content-end mt-2">
                                @if(auth()->id() === $review->user_id)
                                    <button class="btn btn-sm btn-link text-gold p-0 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#editReviewCollapse-{{ $review->id }}"><i class="fa-solid fa-pen-to-square me-1"></i>Edit</button>
                                @endif
                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0 text-decoration-none"><i class="fa-solid fa-trash me-1"></i>Delete</button>
                                </form>
                            </div>
                            
                            <!-- Edit review form -->
                            @if(auth()->id() === $review->user_id)
                                <div class="collapse mt-3" id="editReviewCollapse-{{ $review->id }}">
                                    <form action="{{ route('reviews.update', $review->id) }}" method="POST" class="p-3 bg-light rounded-3">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-2">
                                            <label class="form-label small fw-bold">Your Rating</label>
                                            <div class="star-rating-select">
                                                <input type="radio" id="edit-star5-{{ $review->id }}" name="rating" value="5" {{ $review->rating == 5 ? 'checked' : '' }} /><label for="edit-star5-{{ $review->id }}" class="fa-solid fa-star"></label>
                                                <input type="radio" id="edit-star4-{{ $review->id }}" name="rating" value="4" {{ $review->rating == 4 ? 'checked' : '' }} /><label for="edit-star4-{{ $review->id }}" class="fa-solid fa-star"></label>
                                                <input type="radio" id="edit-star3-{{ $review->id }}" name="rating" value="3" {{ $review->rating == 3 ? 'checked' : '' }} /><label for="edit-star3-{{ $review->id }}" class="fa-solid fa-star"></label>
                                                <input type="radio" id="edit-star2-{{ $review->id }}" name="rating" value="2" {{ $review->rating == 2 ? 'checked' : '' }} /><label for="edit-star2-{{ $review->id }}" class="fa-solid fa-star"></label>
                                                <input type="radio" id="edit-star1-{{ $review->id }}" name="rating" value="1" {{ $review->rating == 1 ? 'checked' : '' }} /><label for="edit-star1-{{ $review->id }}" class="fa-solid fa-star"></label>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label small fw-bold">Your Comment</label>
                                            <textarea name="comment" class="form-control form-control-sm" rows="3" required>{{ $review->comment }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-luxury btn-sm">Update Review</button>
                                    </form>
                                </div>
                            @endif
                        @endif
                    </div>
                @empty
                    <p class="text-muted">No reviews yet for this product. Be the first to share your thoughts!</p>
                @endforelse
            </div>

            <!-- Right Side: Submit Review Form -->
            <div class="col-lg-5">
                <div class="p-4 bg-white rounded-4 shadow-sm border" style="border-color: rgba(232, 220, 203, 0.6) !important;">
                    <h4 class="brand-font mb-3">Share your Experience</h4>
                    @auth
                        @php
                            $userHasReviewed = $product->reviews->where('user_id', auth()->id())->first();
                        @endphp
                        
                        @if($userHasReviewed)
                            <div class="alert alert-info border-0 rounded-3 small">
                                <i class="fa-solid fa-circle-info me-2"></i> You have already reviewed this product. You can edit or delete your existing review in the list.
                            </div>
                        @else
                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="mb-3">
                                    <label class="form-label font-monospace text-uppercase text-muted small" style="letter-spacing: 1px;">Rating</label>
                                    <div class="star-rating-select">
                                        <input type="radio" id="star5" name="rating" value="5" /><label for="star5" class="fa-solid fa-star"></label>
                                        <input type="radio" id="star4" name="rating" value="4" /><label for="star4" class="fa-solid fa-star"></label>
                                        <input type="radio" id="star3" name="rating" value="3" /><label for="star3" class="fa-solid fa-star"></label>
                                        <input type="radio" id="star2" name="rating" value="2" /><label for="star2" class="fa-solid fa-star"></label>
                                        <input type="radio" id="star1" name="rating" value="1" /><label for="star1" class="fa-solid fa-star"></label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="comment" class="form-label font-monospace text-uppercase text-muted small" style="letter-spacing: 1px;">Your Review</label>
                                    <textarea name="comment" id="comment" class="form-control form-control-luxury" rows="4" placeholder="Describe the texture, drape quality, or color fit..." required></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-luxury w-100 py-3">Submit Review</button>
                            </form>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Please log in to submit a review.</p>
                            <a href="{{ route('login') }}" class="btn btn-luxury btn-sm">Login now</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mt-5 pt-5">
            <h3 class="brand-font text-center display-6 mb-4">Complete your Styling</h3>
            <hr class="mx-auto mb-5" style="width: 60px; height: 3px; background-color: var(--gold-accent); border: 0; opacity: 1;">
            
            <div class="row g-4">
                @foreach($relatedProducts as $relProduct)
                    <div class="col-md-6 col-lg-3">
                        <div class="product-card">
                            <div class="product-card-img-wrapper">
                                @if($relProduct->primaryImage)
                                    <img src="{{ asset($relProduct->primaryImage->image_path) }}" class="product-card-img" alt="{{ $relProduct->name }}" onError="this.src='https://placehold.co/400x500/E8DCCB/3B2F2F?text={{ urlencode($relProduct->name) }}'">
                                @else
                                    <img src="https://placehold.co/400x500/E8DCCB/3B2F2F?text={{ urlencode($relProduct->name) }}" class="product-card-img" alt="{{ $relProduct->name }}">
                                @endif
                            </div>
                            <div class="product-card-body">
                                <a href="{{ route('shop.show', $relProduct->slug) }}" class="product-card-title">{{ $relProduct->name }}</a>
                                <div class="product-card-price">PKR {{ number_format($relProduct->price) }}</div>
                                <a href="{{ route('shop.show', $relProduct->slug) }}" class="btn btn-luxury w-100 mt-2 py-2 fs-6">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Shop Catalog - ModestMirror')

@section('content')
<!-- Page Title Header -->
<div class="py-5" style="background-color: var(--light-sand); border-bottom: 1px solid rgba(200, 169, 106, 0.2);">
    <div class="container text-center py-3">
        <h1 class="brand-font display-4 mb-2">The Collections</h1>
        <p class="text-muted lead mb-0">Browse our luxury selection of premium modest hijabs</p>
    </div>
</div>

<!-- Shop Content -->
<div class="container py-5">
    <form action="{{ route('shop.index') }}" method="GET" id="filterForm">
        <div class="row g-4">
            <!-- Left Sidebar Filters (4 columns) -->
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm p-4 rounded-4" style="background-color: var(--white);">
                    <h4 class="brand-font mb-4"><i class="fa-solid fa-sliders me-2 text-gold"></i>Filters</h4>
                    
                    <!-- Search Input -->
                    <div class="mb-4">
                        <label for="search" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Search Products</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-luxury" id="search" name="search" value="{{ request('search') }}" placeholder="Search by name...">
                            <button type="submit" class="btn btn-luxury px-3" style="border-radius: 0 50px 50px 0;"><i class="fa fa-search"></i></button>
                        </div>
                    </div>

                    <!-- Categories Filter -->
                    <div class="mb-4">
                        <label class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px; display: block;">Categories</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input filter-checkbox" type="radio" name="category" id="cat-all" value="" {{ !request('category') ? 'checked' : '' }}>
                            <label class="form-check-label text-muted" for="cat-all">All Categories</label>
                        </div>
                        @foreach($categories as $category)
                            <div class="form-check mb-2">
                                <input class="form-check-input filter-checkbox" type="radio" name="category" id="cat-{{ $category->slug }}" value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'checked' : '' }}>
                                <label class="form-check-label text-muted" for="cat-{{ $category->slug }}">{{ $category->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Fabric Filter -->
                    <div class="mb-4">
                        <label for="fabric" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Fabric Material</label>
                        <select class="form-select form-control-luxury" id="fabric" name="fabric" onchange="this.form.submit()">
                            <option value="">All Fabrics</option>
                            @foreach($fabrics as $fabric)
                                <option value="{{ $fabric }}" {{ request('fabric') == $fabric ? 'selected' : '' }}>{{ $fabric }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Color Filter -->
                    <div class="mb-4">
                        <label for="color" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Color Palette</label>
                        <select class="form-select form-control-luxury" id="color" name="color" onchange="this.form.submit()">
                            <option value="">All Colors</option>
                            @foreach($colors as $color)
                                <option value="{{ $color }}" {{ request('color') == $color ? 'selected' : '' }}>{{ $color }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Reset & Apply Buttons -->
                    <div class="d-grid gap-2 pt-2">
                        <button type="submit" class="btn btn-luxury py-2">Apply Filters</button>
                        @if(request()->anyFilled(['search', 'category', 'fabric', 'color', 'sort_by']))
                            <a href="{{ route('shop.index') }}" class="btn btn-luxury-outline py-2 text-center text-decoration-none">Clear All</a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Catalog Grid (9 columns) -->
            <div class="col-lg-9">
                <!-- Sorting & Top Info -->
                <div class="d-flex justify-content-between align-items-center mb-4 p-3 rounded-4 bg-white shadow-sm">
                    <div class="text-muted" style="font-size: 0.9rem;">
                        Showing <strong class="text-dark">{{ $products->count() }}</strong> hijabs
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <label for="sort_by" class="font-monospace text-uppercase text-muted mb-0" style="font-size: 0.75rem; letter-spacing: 1px; white-space: nowrap;">Sort By:</label>
                        <select class="form-select form-control-luxury py-1" id="sort_by" name="sort_by" onchange="this.form.submit()" style="width: auto;">
                            <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Newest Releases</option>
                            <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="row g-4">
                    @forelse($products as $product)
                        <div class="col-md-6 col-lg-4">
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
                        <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm">
                            <i class="fa-solid fa-circle-info text-gold fs-1 mb-3"></i>
                            <h4 class="brand-font">No Products Match Your Filters</h4>
                            <p class="text-muted">Try clearing search terms or selecting different categories/attributes.</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-luxury mt-3">Reset All Filters</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Submit form automatically on category radio click
        $('.filter-checkbox').on('change', function() {
            $('#filterForm').submit();
        });
    });
</script>
@endsection

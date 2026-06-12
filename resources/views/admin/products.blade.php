@extends('layouts.admin')

@section('title', 'Products Management - ModestMirror')
@section('page_title', 'Products Catalog')

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

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="text-muted mb-0">Total: {{ $products->count() }} Products</h5>
    <button type="button" class="btn btn-luxury btn-sm" data-bs-toggle="modal" data-bs-target="#createProductModal">
        <i class="fa fa-plus me-1"></i> Add Product
    </button>
</div>

<!-- Products Grid Table -->
<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                <tr>
                    <th class="p-3 border-0" style="width: 80px;">Image</th>
                    <th class="p-3 border-0">Name</th>
                    <th class="p-3 border-0">Category</th>
                    <th class="p-3 border-0">Price</th>
                    <th class="p-3 border-0">Fabric / Color</th>
                    <th class="p-3 border-0 text-center">AR Status</th>
                    <th class="p-3 border-0 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td class="p-3 border-bottom-0">
                            <div class="rounded-3 overflow-hidden border" style="width: 50px; height: 55px;">
                                @if($product->primaryImage)
                                    <img src="{{ asset($product->primaryImage->image_path) }}" class="w-100 h-100" style="object-fit: cover;">
                                @else
                                    <img src="https://placehold.co/100x120/E8DCCB/3B2F2F?text={{ urlencode($product->name) }}" class="w-100 h-100" style="object-fit: cover;">
                                @endif
                            </div>
                        </td>
                        <td class="p-3 border-bottom-0">
                            <strong class="text-dark">{{ $product->name }}</strong>
                            <div class="text-muted small">{{ $product->slug }}</div>
                        </td>
                        <td class="p-3 border-bottom-0">{{ $product->category->name }}</td>
                        <td class="p-3 border-bottom-0 fw-semibold">PKR {{ number_format($product->price) }}</td>
                        <td class="p-3 border-bottom-0 text-muted" style="font-size: 0.9rem;">
                            {{ $product->fabric_type }} / {{ $product->color }}
                        </td>
                        <td class="p-3 border-bottom-0 text-center">
                            @if($product->ar_overlay_path)
                                <span class="badge bg-success" title="{{ $product->ar_overlay_path }}"><i class="fa fa-video me-1"></i> AR Overlay</span>
                            @else
                                <span class="badge bg-secondary">No AR Overlay</span>
                            @endif
                        </td>
                        <td class="p-3 border-bottom-0 text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-sm btn-luxury-outline py-1 px-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $product->id }}">
                                    Edit
                                </button>
                                <form action="{{ route('admin.products.delete', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product? All images and AR references will be deleted!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger py-1 px-3">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $product->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content rounded-4 border-0">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title brand-font fs-3" id="editModalLabel{{ $product->id }}">Modify Product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="name{{ $product->id }}" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Product Name</label>
                                                <input type="text" class="form-control form-control-luxury" id="name{{ $product->id }}" name="name" value="{{ $product->name }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="category{{ $product->id }}" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Category</label>
                                                <select class="form-select form-control-luxury" id="category{{ $product->id }}" name="category_id" required>
                                                    @foreach($categories as $cat)
                                                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="price{{ $product->id }}" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Price (PKR)</label>
                                                <input type="number" step="1" class="form-control form-control-luxury" id="price{{ $product->id }}" name="price" value="{{ (int)$product->price }}" min="800" max="2500" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="fabric{{ $product->id }}" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Fabric Type</label>
                                                <input type="text" class="form-control form-control-luxury" id="fabric{{ $product->id }}" name="fabric_type" value="{{ $product->fabric_type }}" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="color{{ $product->id }}" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Color Name</label>
                                                <input type="text" class="form-control form-control-luxury" id="color{{ $product->id }}" name="color" value="{{ $product->color }}" required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="desc{{ $product->id }}" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Description</label>
                                            <textarea class="form-control form-control-luxury" id="desc{{ $product->id }}" name="description" rows="3" required>{{ $product->description }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="image{{ $product->id }}" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Update Primary Product Image</label>
                                            <input type="file" class="form-control form-control-luxury" id="image{{ $product->id }}" name="image" accept="image/*">
                                            <small class="text-muted d-block mt-1">Leaves existing image intact if unselected.</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-luxury-outline btn-sm" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-luxury btn-sm">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No products configured.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title brand-font fs-3" id="createProductModalLabel">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="new_name" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Product Name</label>
                            <input type="text" class="form-control form-control-luxury" id="new_name" name="name" placeholder="e.g. Royal Silk Scarf" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="new_category" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Category</label>
                            <select class="form-select form-control-luxury" id="new_category" name="category_id" required>
                                <option value="" disabled selected>Select Category...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="new_price" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Price (PKR)</label>
                            <input type="number" step="1" class="form-control form-control-luxury" id="new_price" name="price" placeholder="e.g. 1500" min="800" max="2500" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="new_fabric" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Fabric Type</label>
                            <input type="text" class="form-control form-control-luxury" id="new_fabric" name="fabric_type" placeholder="e.g. Silk Chiffon" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="new_color" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Color Name</label>
                            <input type="text" class="form-control form-control-luxury" id="new_color" name="color" placeholder="e.g. Teal Green" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_desc" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Description</label>
                        <textarea class="form-control form-control-luxury" id="new_desc" name="description" rows="3" placeholder="Enter detailed description..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="new_image" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Primary Product Image</label>
                        <input type="file" class="form-control form-control-luxury" id="new_image" name="image" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-luxury-outline btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-luxury btn-sm">Create Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

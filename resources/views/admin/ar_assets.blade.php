@extends('layouts.admin')

@section('title', 'AR Assets Management - ModestMirror')
@section('page_title', 'AR Try-On Overlays')

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

<div class="row g-4">
    <!-- Left Column: Link AR Overlay Asset Form (4 columns) -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
            <h4 class="brand-font mb-4"><i class="fa-solid fa-cloud-arrow-up text-gold me-2"></i>Link Overlay</h4>
            
            <form action="{{ route('admin.ar-assets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="product_id" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Target Product</label>
                    <select class="form-select form-control-luxury" id="product_id" name="product_id" required>
                        <option value="" disabled selected>Select a Product...</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted d-block mt-1">Listing products currently missing AR assets.</small>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Overlay Name</label>
                    <input type="text" class="form-control form-control-luxury" id="name" name="name" placeholder="e.g. Royal Silk Blue AR Overlay" required>
                </div>

                <div class="mb-3">
                    <label for="overlay" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Overlay File (Transparent PNG)</label>
                    <input type="file" class="form-control form-control-luxury" id="overlay" name="overlay" accept="image/png" required>
                    <small class="text-muted d-block mt-1">Must be transparent center PNG, under 2MB.</small>
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="scale_factor" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Scale Factor</label>
                        <input type="number" step="0.01" class="form-control form-control-luxury" id="scale_factor" name="scale_factor" value="1.05" min="0.5" max="2.0" required>
                    </div>
                    <div class="col-6 mb-4">
                        <label for="offset_y" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Offset Y</label>
                        <input type="number" step="0.01" class="form-control form-control-luxury" id="offset_y" name="offset_y" value="-0.05" min="-1.0" max="1.0" required>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-luxury py-2">Link AR Asset</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Current AR Asset Link Index (8 columns) -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
            <div class="card-header bg-white py-3 border-0">
                <h4 class="brand-font mb-0"><i class="fa-solid fa-circle-nodes text-gold me-2"></i>Active Overlay Configurations</h4>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                        <tr>
                            <th class="p-3 border-0">Product Name</th>
                            <th class="p-3 border-0 text-center" style="width: 100px;">Overlay Preview</th>
                            <th class="p-3 border-0">Asset Label</th>
                            <th class="p-3 border-0">Scale / Offset</th>
                            <th class="p-3 border-0">Linked On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                            <tr>
                                <td class="p-3">
                                    <strong>{{ $asset->product->name }}</strong>
                                    <div class="text-muted small">PKR{{ number_format($asset->product->price, 2) }}</div>
                                </td>
                                <td class="p-3 text-center">
                                    <div class="rounded-3 overflow-hidden border p-1" style="width: 60px; height: 60px; background-color: #1e1818;">
                                        <img src="{{ asset($asset->overlay_image_path) }}" class="w-100 h-100" style="object-fit: contain;" alt="AR preview">
                                    </div>
                                </td>
                                <td class="p-3">{{ $asset->name }}</td>
                                <td class="p-3 font-monospace small">
                                    S: {{ $asset->scale_factor }} / Y: {{ $asset->offset_y }}
                                </td>
                                <td class="p-3 text-muted small">
                                    {{ $asset->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">No AR assets uploaded or linked to products.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'AR Try-On Room - ModestMirror')

@section('styles')
<style>
    /* Custom style tweaks for immersive camera room */
    .ar-room-bg {
        background-color: #1e1818;
        color: #f5f0e6;
        min-height: 85vh;
        display: flex;
        align-items: center;
    }
    .ar-room-card {
        background: rgba(30, 24, 24, 0.95);
        border: 1px solid rgba(200, 169, 106, 0.25);
    }
    .sidebar-product-item {
        cursor: pointer;
        transition: var(--transition-smooth);
        border: 1px solid transparent;
    }
    .sidebar-product-item:hover {
        background-color: rgba(200, 169, 106, 0.05);
        border-color: rgba(200, 169, 106, 0.15);
    }
    .sidebar-product-item.active {
        background-color: rgba(200, 169, 106, 0.12);
        border-color: var(--gold-accent);
    }
    .flash-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #ffffff;
        z-index: 100;
        display: none;
        pointer-events: none;
    }
</style>
@endsection

@section('content')
<div class="ar-room-bg py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Left Side: Interactive Webcam Mirror & Controls (8 columns) -->
            <div class="col-lg-8">
                <div class="card ar-room-card rounded-5 shadow-lg p-3">
                    <!-- Webcam View Container -->
                    <div class="ar-viewport rounded-4 border position-relative">
                        <!-- MediaPipe loading overlay -->
                        <div class="ar-loading rounded-4" id="arLoading">
                            <div class="spinner-border text-warning mb-3" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <h4 class="brand-font text-white mb-2">Initializing AR Fitting Room</h4>
                            <p class="text-white-50 small text-center px-4" style="max-width: 400px;">
                                Please allow browser camera access when prompted. Loading AI Face Mesh modules...
                            </p>
                        </div>

                        <!-- Flash light effect overlay -->
                        <div class="flash-overlay rounded-4" id="flash-effect"></div>

                        <!-- Camera Feeds -->
                        <video id="webcamVideo" class="ar-video rounded-4" playsinline muted style="display: none;"></video>
                        <canvas id="arCanvas" class="ar-canvas rounded-4"></canvas>

                        <!-- HUD Badges overlay -->
                        <div class="ar-ui-overlay">
                            <span class="badge bg-danger" id="camera-status-badge">
                                <i class="fa-solid fa-video-slash me-1"></i> Starting Camera
                            </span>
                            <span class="badge bg-warning text-dark" id="facemesh-status-badge">
                                <i class="fa-solid fa-arrows-rotate me-1"></i> Tracking Face
                            </span>
                        </div>
                    </div>

                    <!-- Bottom Controls Toolbar (Glassmorphic) -->
                    <div class="ar-controls-panel d-flex justify-content-between align-items-center mt-3" style="background: rgba(59, 47, 47, 0.4); border-color: rgba(200, 169, 106, 0.2);">
                        <div class="d-flex align-items-center gap-3">
                            <button class="btn btn-gold btn-sm py-2 px-3" id="btn-screenshot" title="Take a Photo Snapshot">
                                <i class="fa fa-camera me-1"></i> Snapshot
                            </button>
                            <button class="btn btn-luxury-outline text-white border-white btn-sm py-2 px-3" id="btn-reset" title="Recalibrate Webcam Tracking">
                                <i class="fa fa-arrows-rotate"></i>
                            </button>
                        </div>
                        <div class="text-white-50 font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                            Active Overlay: <span class="text-white fw-bold" id="toolbar-product-name">-</span>
                        </div>
                    </div>

                    <!-- Manual Adjustments Controls Panel -->
                    <div class="ar-controls-panel mt-3 text-white" style="background: rgba(59, 47, 47, 0.4); border-color: rgba(200, 169, 106, 0.2);">
                        <h6 class="font-monospace text-uppercase text-gold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;"><i class="fa-solid fa-sliders me-2"></i>Manual Calibration Adjustments</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <label class="small text-white-50">Zoom Adjust:</label>
                                    <span class="badge bg-gold text-dark" id="zoom-val">1.00x</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-luxury-outline text-white border-white flex-grow-1" id="btn-zoom-out"><i class="fa-solid fa-minus"></i> Out</button>
                                    <button class="btn btn-sm btn-luxury-outline text-white border-white flex-grow-1" id="btn-zoom-in"><i class="fa-solid fa-plus"></i> In</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <label class="small text-white-50">Vertical Position:</label>
                                    <span class="badge bg-gold text-dark" id="offset-y-val">0.0px</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-luxury-outline text-white border-white flex-grow-1" id="btn-offset-up"><i class="fa-solid fa-arrow-up"></i> Up</button>
                                    <button class="btn btn-sm btn-luxury-outline text-white border-white flex-grow-1" id="btn-offset-down"><i class="fa-solid fa-arrow-down"></i> Down</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <label class="small text-white-50">Horizontal Position:</label>
                                    <span class="badge bg-gold text-dark" id="offset-x-val">0.0px</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-luxury-outline text-white border-white flex-grow-1" id="btn-offset-left"><i class="fa-solid fa-arrow-left"></i> Left</button>
                                    <button class="btn btn-sm btn-luxury-outline text-white border-white flex-grow-1" id="btn-offset-right"><i class="fa-solid fa-arrow-right"></i> Right</button>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mt-2 border-top pt-3" style="border-color: rgba(245, 240, 230, 0.1) !important;">
                            <div class="col-md-6 d-flex align-items-center gap-2">
                                <label class="small text-white-50">Face Shape Mode:</label>
                                <select class="form-select form-select-sm bg-transparent text-white border-gold" id="select-face-shape" style="border-color: var(--gold-accent) !important; color: white;">
                                    <option value="auto" class="text-dark" selected>Auto Detect Shape</option>
                                    <option value="oval" class="text-dark">Oval Face Shape</option>
                                    <option value="round" class="text-dark">Round Face Shape</option>
                                    <option value="square" class="text-dark">Square Face Shape</option>
                                    <option value="heart" class="text-dark">Heart Face Shape</option>
                                    <option value="long" class="text-dark">Long Face Shape</option>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-center justify-content-end gap-2">
                                <span class="small text-white-50">Detected Face Shape:</span>
                                <span class="badge bg-info text-white" id="detected-shape-badge">Auto (Analyzing...)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Product Details & Cart Sync Panel (4 columns) -->
            <div class="col-lg-4">
                <div class="card ar-room-card rounded-5 shadow-lg h-100 p-4 d-flex flex-column text-white">
                    <h4 class="brand-font text-white mb-3"><i class="fa-solid fa-store text-gold me-2"></i>Select Hijab</h4>
                    <p class="text-white-50 small mb-4">Select a luxury piece below. The overlay will automatically scale and tilt dynamically with your head movement.</p>

                    <!-- Scrollable Product Selector -->
                    <div class="flex-grow-1 mb-4" style="max-height: 280px; overflow-y: auto; border-bottom: 1px solid rgba(245, 240, 230, 0.1); padding-bottom: 15px;">
                        <div class="d-flex flex-column gap-2">
                            @forelse($products as $prod)
                                <div class="sidebar-product-item p-2 rounded-4 d-flex align-items-center gap-3 {{ $selectedProduct && $selectedProduct->id == $prod->id ? 'active' : '' }}"
                                     data-id="{{ $prod->id }}"
                                     data-name="{{ $prod->name }}"
                                     data-price="{{ $prod->price }}"
                                     data-overlay="{{ asset($prod->ar_overlay_path) }}"
                                     data-cart-route="{{ route('cart.add', $prod->id) }}"
                                     data-wishlist-route="{{ route('wishlist.add', $prod->id) }}">
                                     
                                    <div class="rounded-3 overflow-hidden" style="width: 45px; height: 50px;">
                                        @if($prod->primaryImage)
                                            <img src="{{ asset($prod->primaryImage->image_path) }}" class="w-100 h-100" style="object-fit: cover;">
                                        @else
                                            <img src="https://placehold.co/100x120/E8DCCB/3B2F2F?text={{ urlencode($prod->name) }}" class="w-100 h-100" style="object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-white small" style="line-height: 1.2;">{{ $prod->name }}</div>
                                        <div class="text-muted" style="font-size: 0.7rem; color: #c8a96a !important;">{{ $prod->fabric_type }} | {{ $prod->color }}</div>
                                    </div>
                                    <div class="fw-semibold text-white small">PKR {{ number_format($prod->price) }}</div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <p class="text-white-50 small mb-0">No AR compatible products found.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Selected Product Specs Details -->
                    <div class="mb-4">
                        <h6 class="font-monospace text-uppercase text-gold mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Overlay Attributes</h6>
                        <table class="table table-sm table-borderless text-white-50 small mb-0">
                            <tbody>
                                <tr>
                                    <td class="p-1" style="width: 100px;">Fabric:</td>
                                    <td class="p-1 text-white fw-semibold" id="spec-fabric">-</td>
                                </tr>
                                <tr>
                                    <td class="p-1">Color:</td>
                                    <td class="p-1 text-white fw-semibold" id="spec-color">-</td>
                                </tr>
                                <tr>
                                    <td class="p-1" style="vertical-align: top;">Simulation:</td>
                                    <td class="p-1 text-white-50" id="spec-description">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Direct E-Commerce Checkout Integration -->
                    <div class="mt-auto">
                        <div class="row g-2">
                            <div class="col-8">
                                <form id="ar-cart-form" action="#" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-gold w-100 py-3 font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;"><i class="fa fa-shopping-bag me-1"></i> Add To Bag</button>
                                </form>
                            </div>
                            <div class="col-4">
                                <form id="ar-wishlist-form" action="#" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-luxury-outline border-white text-white w-100 py-3" title="Save to Wishlist"><i class="far fa-heart"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- MediaPipe Face Mesh Library CDN -->
<script src="https://cdn.jsdelivr.net/npm/@mediapipe/face_mesh/face_mesh.js" crossorigin="anonymous"></script>

<script>
    // Global configurations needed by ar-tryon.js
    const isUserLoggedIn = @json(auth()->check());
    const saveScreenshotRoute = "{{ route('ar.save-screenshot') }}";
</script>

<!-- Local AR Controller script -->
<script src="{{ asset('js/ar-tryon.js') }}"></script>
@endsection

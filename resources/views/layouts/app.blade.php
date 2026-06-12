<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ModestMirror - Premium Virtual Hijab Try-On')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    
    <!-- FontAwesome 6 Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
    
    <!-- Custom Style -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    @yield('styles')
</head>
<body>

    <!-- Glassmorphic Navbar -->
    <nav class="navbar navbar-expand-lg navbar-luxury sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                Modest<span>Mirror</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() == 'shop.index' ? 'active' : '' }}" href="{{ route('shop.index') }}">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() == 'ar.tryon' ? 'active' : '' }}" href="{{ route('ar.tryon') }}">AR Try-On</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#categories">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#contact">Contact</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    @auth
                        <!-- Wishlist -->
                        <a href="{{ route('wishlist.index') }}" class="position-relative me-3 text-dark fs-5 hover-gold-text" title="Wishlist">
                            <i class="far fa-heart"></i>
                            @php
                                $wishlistCount = \App\Models\Wishlist::where('user_id', auth()->id())->count();
                            @endphp
                            @if($wishlistCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger" style="font-size: 0.65rem;">
                                    {{ $wishlistCount }}
                                </span>
                            @endif
                        </a>
                        
                        <!-- Cart -->
                        <a href="{{ route('cart.index') }}" class="position-relative me-4 text-dark fs-5 hover-gold-text" title="Cart">
                            <i class="fa fa-shopping-bag"></i>
                            @php
                                $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->count();
                            @endphp
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-dark" style="font-size: 0.65rem; background-color: var(--mocha-brown) !important;">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>

                        <!-- User Profile Dropdown -->
                        <div class="dropdown">
                            <a class="btn btn-luxury-outline dropdown-toggle btn-sm" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-regular fa-user me-1"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" aria-labelledby="userDropdown" style="background-color: var(--white); border-radius: 12px;">
                                @if(auth()->user()->isAdmin())
                                    <li><a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge me-2 text-warning"></i>Admin Dashboard</a></li>
                                @endif
                                <li><a class="dropdown-item py-2" href="{{ route('dashboard') }}"><i class="fa-regular fa-id-card me-2"></i>My Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger"><i class="fa-solid fa-sign-out me-2"></i>Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-luxury-outline btn-sm me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-luxury btn-sm">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-luxury" id="contact">
        <div class="container">
            <!-- Newsletter Subscription Row -->
            <div class="row mb-5 justify-content-center border-bottom pb-5" style="border-color: rgba(245, 240, 230, 0.1);">
                <div class="col-md-8 text-center">
                    <h3 class="font-brand text-white mb-2" style="color: var(--gold-accent);">Join the Modest Mirror Club</h3>
                    <p class="mb-4" style="color: #d8cfc2;">Subscribe for latest collections, exclusive offers, and virtual styling guides.</p>
                    <form id="newsletterForm" class="d-flex justify-content-center mx-auto" style="max-width: 500px;">
                        @csrf
                        <div class="input-group">
                            <input type="email" name="email" class="form-control py-2" placeholder="Your Email Address" required style="background: rgba(255,255,255,0.12); border: 1px solid var(--gold-accent); color: white;">
                            <button type="submit" class="btn btn-luxury px-4">Subscribe</button>
                        </div>
                    </form>
                    <div id="newsletterMessage" class="mt-3 small" style="display:none;"></div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <h3 class="footer-logo mb-3">Modest<span>Mirror</span></h3>
                    <p class="text-white-50">Providing modern modesty through cutting-edge technology. Experience luxury virtual styling and e-commerce for high-fashion modest wear.</p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="fs-4 text-white-50"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="fs-4 text-white-50"><i class="fab fa-pinterest"></i></a>
                        <a href="#" class="fs-4 text-white-50"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="fs-4 text-white-50"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1">
                    <h5 class="text-white mb-3 font-brand">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}">Home</a></li>
                        <li class="mb-2"><a href="{{ route('shop.index') }}">Shop Catalog</a></li>
                        <li class="mb-2"><a href="{{ route('ar.tryon') }}">AR Try-On Room</a></li>
                        <li class="mb-2"><a href="{{ route('home') }}#about">Our Story</a></li>
                    </ul>
                </div>
                <div class="col-lg-2">
                    <h5 class="text-white mb-3 font-brand">Categories</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('shop.index', ['category' => 'casual-elegance']) }}">Casual Elegance</a></li>
                        <li class="mb-2"><a href="{{ route('shop.index', ['category' => 'formal-chic']) }}">Formal Chic</a></li>
                        <li class="mb-2"><a href="{{ route('shop.index', ['category' => 'bridal-luxury']) }}">Bridal Luxury</a></li>
                        <li class="mb-2"><a href="{{ route('shop.index', ['category' => 'everyday-basics']) }}">Everyday Basics</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="text-white mb-3 font-brand">Contact Details</h5>
                    <p class="text-white-50 mb-2"><i class="fa fa-envelope me-2 text-gold"></i> info@modestmirror.com</p>
                    <p class="text-white-50 mb-2"><i class="fa fa-phone me-2 text-gold"></i> +92 300 1234567</p>
                    <p class="text-white-50 mb-2"><i class="fa fa-map-marker-alt me-2 text-gold"></i> Burewala, Pakistan</p>
                </div>
            </div>
            <hr class="mt-5 mb-4" style="border-color: rgba(245, 240, 230, 0.1);">
            <div class="text-center text-white-50" style="font-size: 0.9rem;">
                <p>&copy; {{ date('Y') }} ModestMirror. All Rights Reserved. Crafted for real-time elegance.</p>
            </div>
        </div>
    </footer>

    <!-- Floating Chatbot UI -->
    <div id="aiStylistBot" class="ai-stylist-widget">
        <button id="chatbotToggle" class="chatbot-toggle-btn shadow-lg" title="AI Stylist Assistant">
            <i class="fa-solid fa-comments fs-4"></i>
        </button>
        <div id="chatbotWindow" class="chatbot-window shadow-lg d-none">
            <div class="chatbot-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="stylist-avatar-icon me-2">
                        <i class="fa-solid fa-wand-magic-sparkles text-gold"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-white font-brand" style="font-size: 0.95rem;">AI Fashion Stylist</h6>
                        <small class="text-gold" style="font-size: 0.65rem;">Always Online</small>
                    </div>
                </div>
                <button id="chatbotClose" class="btn-close btn-close-white btn-sm" aria-label="Close"></button>
            </div>
            <div class="chatbot-body" id="chatbotBody">
                <div class="chat-bubble bot-bubble">
                    Hello! I am your ModestMirror AI Fashion Stylist. I can help you find the perfect hijab for your outfit, face shape, or any occasion.
                </div>
                <div class="chatbot-suggestions">
                    <button class="chat-suggest-btn" data-prompt="Which hijab suits my face shape?">Which hijab suits my face shape?</button>
                    <button class="chat-suggest-btn" data-prompt="Recommend something for a casual outfit">Recommend casual hijabs</button>
                    <button class="chat-suggest-btn" data-prompt="Suggest premium silk hijabs for a formal event">Suggest premium silk hijabs</button>
                </div>
            </div>
            <div class="chatbot-footer border-top p-2 bg-light">
                <form id="chatbotForm" class="d-flex gap-1 mb-0">
                    <input type="text" id="chatbotInput" class="form-control form-control-sm" placeholder="Ask your stylist..." required>
                    <button type="submit" class="btn btn-luxury btn-sm px-3 py-1"><i class="fa-solid fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>

    <!-- AJAX Scripts for Chatbot and Newsletter -->
    <script>
        $(document).ready(function() {
            // Newsletter AJAX Subscription
            $('#newsletterForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const emailInput = form.find('input[name="email"]');
                const msgDiv = $('#newsletterMessage');
                
                msgDiv.hide().removeClass('text-success text-danger');
                
                $.ajax({
                    url: "{{ route('newsletter.subscribe') }}",
                    method: "POST",
                    data: form.serialize(),
                    success: function(response) {
                        msgDiv.show().removeClass('text-danger').addClass('text-success').html('<i class="fa-solid fa-circle-check"></i> ' + response.message);
                        emailInput.val('');
                    },
                    error: function(xhr) {
                        let message = 'Something went wrong. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        msgDiv.show().removeClass('text-success').addClass('text-danger').html('<i class="fa-solid fa-circle-exclamation"></i> ' + message);
                    }
                });
            });

            // Chatbot Toggle
            $('#chatbotToggle').on('click', function() {
                $('#chatbotWindow').toggleClass('d-none');
                scrollToChatBottom();
            });

            $('#chatbotClose').on('click', function() {
                $('#chatbotWindow').addClass('d-none');
            });

            // Suggestions click
            $(document).on('click', '.chat-suggest-btn', function() {
                const prompt = $(this).data('prompt');
                submitChatMessage(prompt);
            });

            // Chatbot Submit Message
            $('#chatbotForm').on('submit', function(e) {
                e.preventDefault();
                const input = $('#chatbotInput');
                const msg = input.val().trim();
                if (msg) {
                    submitChatMessage(msg);
                    input.val('');
                }
            });

            function scrollToChatBottom() {
                const chatBody = $('#chatbotBody');
                if (chatBody.length) {
                    chatBody.scrollTop(chatBody[0].scrollHeight);
                }
            }

            function submitChatMessage(message) {
                // Append User Bubble
                const chatBody = $('#chatbotBody');
                chatBody.append(`<div class="chat-bubble user-bubble">${escapeHtml(message)}</div>`);
                scrollToChatBottom();
                
                // Append Temporary Bot Loader
                const loaderId = 'bot-loader-' + Date.now();
                chatBody.append(`<div class="chat-bubble bot-bubble" id="${loaderId}"><i class="fa-solid fa-spinner fa-spin"></i> Thinking...</div>`);
                scrollToChatBottom();
                
                $.ajax({
                    url: "{{ route('ai-stylist.message') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { message: message },
                    success: function(response) {
                        $(`#${loaderId}`).remove();
                        
                        // Append Bot Response Text
                        chatBody.append(`<div class="chat-bubble bot-bubble">${response.response}</div>`);
                        
                        // Append Bot Recommended Products if any
                        if (response.products && response.products.length > 0) {
                            response.products.forEach(p => {
                                const shopUrl = "{{ url('/shop') }}/" + p.slug;
                                const pHtml = `
                                    <a href="${shopUrl}" class="chat-product-card d-flex align-items-center text-decoration-none">
                                        <img src="${p.image}" class="chat-product-img" alt="${p.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        <div class="chat-product-info ps-2 flex-grow-1">
                                            <div class="chat-product-title fw-bold" style="font-size: 0.8rem; color: var(--primary-coffee);">${escapeHtml(p.name)}</div>
                                            <div class="chat-product-price text-muted" style="font-size: 0.75rem;">PKR ${p.price}</div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-muted small pe-1"></i>
                                    </a>
                                `;
                                chatBody.append(pHtml);
                            });
                        }
                        scrollToChatBottom();
                    },
                    error: function() {
                        $(`#${loaderId}`).remove();
                        chatBody.append(`<div class="chat-bubble bot-bubble text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Sorry, I couldn't reach my styling server. Please try again later.</div>`);
                        scrollToChatBottom();
                    }
                });
            }

            function escapeHtml(string) {
                return String(string).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }
        });
    </script>

    @yield('scripts')
</body>
</html>

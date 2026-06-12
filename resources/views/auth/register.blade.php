@extends('layouts.app')

@section('title', 'Register - ModestMirror')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center py-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg p-4" style="background-color: var(--white); border-radius: 20px;">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h2 class="brand-font fs-1 mb-2">Create Account</h2>
                        <p class="text-muted">Join ModestMirror to unlock AR try-ons & save styling highlights</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger border-0 rounded-4" style="font-size: 0.9rem;">
                            <ul class="mb-0 list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fa-solid fa-circle-exclamation me-2"></i>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Full Name</label>
                            <input type="text" class="form-control form-control-luxury" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. Fatima Al-Sayed">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Email Address</label>
                            <input type="email" class="form-control form-control-luxury" id="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Password</label>
                                <input type="password" class="form-control form-control-luxury" id="password" name="password" required placeholder="Min 8 characters">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="password_confirmation" class="form-label font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Confirm Password</label>
                                <input type="password" class="form-control form-control-luxury" id="password_confirmation" name="password_confirmation" required placeholder="Repeat password">
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-luxury w-100 py-3">Register & Explore</button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">Already have an account? <a href="{{ route('login') }}" class="hover-gold-text fw-semibold" style="color: var(--mocha-brown); text-decoration: none;">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.pelanggan')

@section('title', 'Masuk - Tikako')

@section('content')

<div class="container py-5">
    <div class="row justify-content-center align-items-center">
        <div class="col-lg-10">
            {{-- Card Wrapper --}}
            <div class="card border-0 shadow-lg overflow-hidden rounded-4" style="min-height: 500px;">
                <div class="row g-0 h-100">
                    
                    {{-- Left Column: Visual Banner --}}
                    <div class="col-lg-6 d-none d-lg-block position-relative bg-dark">
                        {{-- Placeholder Image Path --}}
                        <img src="{{ asset('storage/site_images/tikako_banner.png') }}" 
                             class="position-absolute w-100 h-100" 
                             style="object-fit: cover; opacity: 0.8; top: 0; left: 0;" 
                             alt="Login Banner">
                        
                        {{-- Text Overlay --}}
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white p-5 text-center" style="background: rgba(0,0,0,0.4); z-index: 2;">
                            <h2 class="fw-bold mb-3 display-6" style="font-family: 'Charm', cursive;">Welcome Back!</h2>
                            <p class="lead fs-6">Enjoy the convenience of ordering your favorite food without queuing.</p>
                        </div>
                    </div>

                    {{-- Right Column: Login Form --}}
                    <div class="col-lg-6 d-flex align-items-center bg-white">
                        <div class="card-body p-4 p-lg-5 w-100">
                            
                            <div class="text-center mb-5">
                                <h3 class="fw-bold text-dark">Sign In</h3>
                                <p class="text-muted small">Please log in to continue your order.</p>
                            </div>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                
                                {{-- Email Field --}}
                                <div class="mb-4">
                                    <label for="email" class="form-label small fw-bold text-secondary">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-secondary"><i class="bi bi-envelope"></i></span>
                                        <input type="email" 
                                               name="email" 
                                               class="form-control bg-light border-start-0 py-2 @error('email') is-invalid @enderror" 
                                               id="email" 
                                               placeholder="example@email.com" 
                                               value="{{ old('email') }}" 
                                               required autofocus>
                                    </div>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Password Field --}}
                                <div class="mb-4">
                                    <label for="password" class="form-label small fw-bold text-secondary">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-secondary"><i class="bi bi-lock"></i></span>
                                        <input type="password" 
                                               name="password" 
                                               class="form-control bg-light border-start-0 py-2 @error('password') is-invalid @enderror" 
                                               id="password" 
                                               placeholder="Enter password" 
                                               required>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Login Button --}}
                                <div class="d-grid mb-4 mt-5">
                                    <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm py-3 rounded-3">
                                        Sign In <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>

                                {{-- Register Link --}}
                                <div class="text-center text-muted small">
                                    Don't have an account? 
                                    <a href="{{ route('register') }}" class="text-decoration-none fw-bold text-primary">
                                        Register here
                                    </a>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
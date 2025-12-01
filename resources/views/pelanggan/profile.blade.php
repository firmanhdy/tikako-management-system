@extends('layouts.pelanggan')

@section('title', 'My Profile - Tikako')

@section('content')

<div class="container py-5">
    <div class="row g-4">
        
        {{-- LEFT COLUMN: QUICK PROFILE CARD --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-4 h-100">
                <div class="mb-3">
                    {{-- Large Initial Avatar --}}
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bold shadow-sm" 
                         style="width: 100px; height: 100px; font-size: 2.5rem;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <p class="text-muted small mb-3">{{ $user->email }}</p>
                
                <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 mb-4">
                    Loyal Customer
                </div>

                <hr class="opacity-10">

                <div class="text-start small text-muted mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Joined Since</span>
                        <span class="fw-bold text-dark">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>User ID</span>
                        <span class="fw-bold text-dark">#{{ $user->id }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: EDIT PROFILE FORM --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold">Edit Profile</h5>
                </div>
                <div class="card-body p-4">
                    
                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        
                        {{-- Full Name Input --}}
                        <div class="mb-4">
                            <label for="name" class="form-label small fw-bold text-secondary">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                                <input type="text" 
                                       name="name" 
                                       class="form-control bg-light border-start-0 ps-0 @error('name') is-invalid @enderror" 
                                       id="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required>
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email Input --}}
                        <div class="mb-4">
                            <label for="email" class="form-label small fw-bold text-secondary">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                <input type="email" 
                                       name="email" 
                                       class="form-control bg-light border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                       id="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Warning Alert --}}
                        <div class="alert alert-warning d-flex align-items-center mt-4 mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                            <div class="small">
                                <strong>Attention:</strong> Changing your email will modify your login credentials. Ensure the email is active.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                                <i class="bi bi-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
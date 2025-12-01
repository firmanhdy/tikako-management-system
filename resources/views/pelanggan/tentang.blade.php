@extends('layouts.pelanggan')

@section('title', 'About Us - Tikako')

@section('content')

<div class="container py-5">
    
    {{-- SECTION 1: STORY & PHOTO --}}
    <div class="row align-items-center mb-5">
        {{-- Photo/Visual (Left) --}}
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="position-relative">
                {{-- Image pointing to storage/site_images --}}
                <img src="{{ asset('storage/site_images/tentang.png') }}" 
                    onerror="this.src='https://placehold.co/600x400?text=Tikako+Atmosphere'" 
                    alt="Tikako Atmosphere" 
                    class="img-fluid rounded-4 shadow-lg w-100"
                    style="object-fit: cover; height: 400px;">
                
                {{-- Decorative Boxes --}}
                <div class="position-absolute bg-warning rounded-3 shadow-sm" 
                    style="width: 100px; height: 100px; bottom: -20px; right: -20px; z-index: -1;"></div>
                <div class="position-absolute bg-dark rounded-3 shadow-sm" 
                    style="width: 100px; height: 100px; top: -20px; left: -20px; z-index: -1;"></div>
            </div>
        </div>

        {{-- Story Text (Right) --}}
        <div class="col-lg-6 ps-lg-5">
            <h5 class="text-warning fw-bold text-uppercase mb-2">Our Story</h5>
            <h1 class="display-5 fw-bold mb-4">Merging with Nature</h1>
            
            <p class="text-muted lead fs-6">
                Tikako started in 2021 and is known for its unique concept: 
                combining the dining experience with a natural atmosphere. 
                Visitors can enjoy dishes while 
                <span class="fw-bold text-dark bg-warning bg-opacity-25 px-1">soaking their feet in the clear river stream.</span>
            </p>
            <p class="text-secondary">
                We are committed to increasing service efficiency through this web-based ordering system. 
                Our goal is to provide easy ordering without having to approach the cashier, 
                allowing visitors to relax and enjoy nature more fully.
            </p>
        </div>
    </div>

    {{-- SECTION 2: KEY ADVANTAGES (3 Columns) --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4 hover-card">
                <div class="mb-3 text-warning">
                    <i class="bi bi-water fs-1"></i>
                </div>
                <h5 class="fw-bold">Natural Tourism</h5>
                <p class="text-muted small mb-0">
                    Enjoy the sensation of culinary in the cool and refreshing river stream of Kalilunjar Village.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4 hover-card">
                <div class="mb-3 text-warning">
                    <i class="bi bi-cup-hot-fill fs-1"></i>
                </div>
                <h5 class="fw-bold">Authentic Java Culinary</h5>
                <p class="text-muted small mb-0">
                    Serving authentic Javanese cuisine and selected coffee that indulges the palate.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4 hover-card">
                <div class="mb-3 text-warning">
                    <i class="bi bi-phone-fill fs-1"></i>
                </div>
                <h5 class="fw-bold">Digital Service</h5>
                <p class="text-muted small mb-0">
                    Order food directly from your table using our modern web-based system.
                </p>
            </div>
        </div>
    </div>

    {{-- SECTION 3: LOCATION (CTA) --}}
    <div class="bg-dark text-white rounded-4 p-5 text-center position-relative overflow-hidden">
        {{-- Transparent Background Decoration --}}
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-white opacity-10" 
             style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
        
        <div class="position-relative z-1">
            <h2 class="fw-bold mb-3">Visit Us</h2>
            <p class="mb-4 text-white-50">
                Located in Kalilunjar Village, Banjarmangu District, Banjarnegara Regency.
            </p>
            <a href="https://maps.google.com/?q=Tikako+Caffe" target="_blank" class="btn btn-warning fw-bold px-4 py-2">
                <i class="bi bi-map-fill me-2"></i> View on Google Maps
            </a>
        </div>
    </div>

</div>

{{-- Additional CSS for Hover Effect --}}
<style>
    .hover-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>

@endsection
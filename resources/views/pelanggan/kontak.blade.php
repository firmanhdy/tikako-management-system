@extends('layouts.pelanggan')

@section('title', 'Contact Us - Tikako')

@section('content')

<div class="container py-5">
    {{-- Main Card Wrapper --}}
    <div class="card shadow-lg border-0 overflow-hidden rounded-4">
        <div class="row g-0">
            
            {{-- LEFT SECTION: Contact Info --}}
            <div class="col-lg-5 text-dark p-5 d-flex flex-column justify-content-center position-relative" 
                 style="background-color: #ffc107;">
                <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient opacity-25"></div>
            
                <div class="position-relative z-1">
                    <h2 class="fw-bold mb-4 text-dark">Contact Us</h2>
                    <p class="mb-5 text-dark fs-6 opacity-75">
                        Have a question, need a reservation, or just want to say hello? We are ready to hear from you.
                    </p>
                    
                    {{-- Address Info --}}
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-dark bg-opacity-25 p-2 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-geo-alt-fill fs-5 text-white"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Address</h6>
                            <p class="mb-0 text-dark small opacity-75">Jl. Raya Tikako No. 123, Banjarnegara, Central Java</p>
                        </div>
                    </div>
                    
                    {{-- WhatsApp Info --}}
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-dark bg-opacity-25 p-2 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-whatsapp fs-5 text-white"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">WhatsApp</h6>
                            <p class="mb-0 text-dark small opacity-75">+62 856-0040-5568</p>
                        </div>
                    </div>

                    {{-- Hours Info --}}
                    <div class="d-flex align-items-start">
                        <div class="bg-dark bg-opacity-25 p-2 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-clock-fill fs-5 text-white"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Operating Hours</h6>
                            <p class="mb-0 text-dark small opacity-75">Every Day: 10:00 AM - 10:00 PM WIB</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT SECTION: Feedback Form --}}
            <div class="col-lg-7 bg-white p-5">
                <h4 class="fw-bold text-dark mb-2">Feedback & Suggestions</h4>
                <p class="text-muted small mb-4">Your input is highly valuable for Tikako Caffe's improvement.</p>

                <form action="{{ route('feedback.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3">
                        {{-- Name Input --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Full Name</label>
                            <input type="text" name="name" class="form-control bg-light border-0 py-2" placeholder="Your Name" required>
                        </div>

                        {{-- Email Input --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-secondary">Email</label>
                            <input type="email" name="email" class="form-control bg-light border-0 py-2" value="{{ Auth::user()->email ?? '' }}" placeholder="Your Email">
                        </div>
                        
                        {{-- Rating Select --}}
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">Satisfaction Level</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-star-fill text-warning"></i></span>
                                <select name="rating" class="form-select bg-light border-0 py-2">
                                    <option value="5">Excellent (5/5)</option>
                                    <option value="4">Good (4/5)</option>
                                    <option value="3">Average (3/5)</option>
                                    <option value="2">Poor (2/5)</option>
                                    <option value="1">Terrible (1/5)</option>
                                </select>
                            </div>
                        </div>

                        {{-- Message Textarea --}}
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary">Message / Suggestion</label>
                            <textarea name="message" class="form-control bg-light border-0" rows="5" placeholder="Share your experience here..." required></textarea>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                                Submit Feedback <i class="bi bi-send ms-2"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection
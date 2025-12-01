@extends('layouts.admin')

@section('title', 'Table QR Code Generator - Tikako')

@section('content')

    <h1 class="display-6 fw-bold mb-4">QR Code Generator</h1>

    <div class="row">
        {{-- Centered Card --}}
        <div class="col-md-6 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    
                    {{-- Instructions --}}
                    <h5 class="card-title fw-bold mb-3">Create Table Sticker</h5>
                    <p class="text-muted small mb-4">
                        Enter the table number below to generate a QR Code. 
                        When scanned, customers will be redirected to the menu page with the table number automatically applied.
                    </p>

                    {{-- Generator Form --}}
                    <form action="{{ route('admin.qrcode.print') }}" method="POST" target="_blank">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Table Number</label>
                            <input type="number" 
                                   name="nomor_meja" 
                                   class="form-control form-control-lg" 
                                   placeholder="Example: 5" 
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-qr-code me-2"></i> Generate & Print
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
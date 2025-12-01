@extends('layouts.pelanggan')

@section('title', 'Order Received - Tikako')

@section('content')

<div class="container py-5 d-flex flex-column justify-content-center align-items-center" style="min-height: 70vh;">
    
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden text-center p-4 position-relative" style="max-width: 450px; width: 100%;">
        
        {{-- Top Accent Line --}}
        <div class="position-absolute top-0 start-0 w-100 bg-success" style="height: 6px;"></div>

        <div class="card-body">
            
            {{-- Animated Success Icon --}}
            <div class="mb-3 mt-2">
                <div class="success-icon-wrapper">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                </div>
            </div>

            <h2 class="fw-bold text-dark mb-2">Order Received!</h2>
            <p class="text-muted mb-4 small">
                Thank you! Your order has been sent to our kitchen and is currently being prepared.
            </p>

            {{-- Order Number Box (Primary Focus) --}}
            <div class="bg-light border border-secondary-subtle rounded-3 p-4 mb-4">
                <div class="text-uppercase text-secondary fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">
                    Your Order Number
                </div>
                <div class="display-3 fw-bold text-primary mt-1 mb-1">
                    #{{ $orderId }}
                </div>
                <div class="badge bg-warning text-dark rounded-pill px-3">
                    <i class="bi bi-hourglass-split me-1"></i> Awaiting Payment
                </div>
            </div>

            <p class="small text-secondary mb-4">
                Please show this number to the cashier for payment.
            </p>

            {{-- Action Buttons --}}
            <div class="d-grid gap-2">
                <a href="{{ route('orders.myOrders') }}" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-receipt me-2"></i> View Order Status
                </a>
                <a href="{{ route('menu.indexPage') }}" class="btn btn-outline-secondary rounded-pill fw-bold">
                    Back to Menu
                </a>
            </div>

        </div>
    </div>

</div>

{{-- CSS Pop-Up Animation --}}
<style>
    .success-icon-wrapper {
        animation: popSuccess 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        transform: scale(0);
    }
    @keyframes popSuccess {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>

@endsection
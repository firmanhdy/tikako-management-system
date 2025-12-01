@extends('layouts.pelanggan')

@section('title', $menu->nama_menu . ' - Tikako')

@section('content')

<div class="container py-5">
    <div class="row align-items-center">
        
        {{-- LEFT SECTION: MENU PHOTO --}}
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card shadow-lg border-0 overflow-hidden rounded-4">
                {{-- Image Logic: Use actual photo or placeholder --}}
                @if ($menu->foto)
                    <img src="{{ asset('storage/' . $menu->foto) }}" 
                         class="w-100" 
                         alt="{{ $menu->nama_menu }}"
                         style="height: 450px; object-fit: cover;">
                @else
                <img src="https://placehold.co/600x600?text=TIKAKO+MENU" 
                             class="w-100" 
                             alt="No Photo"
                             style="height: 450px; object-fit: cover;">
                @endif
            </div>
        </div>

        {{-- RIGHT SECTION: DETAILS & ORDER BUTTON --}}
        <div class="col-md-6 ps-md-5">
            
            {{-- Category & Status Badges --}}
            <div class="mb-3">
                <span class="badge bg-secondary me-1">{{ $menu->kategori }}</span>
                
                @if ($menu->is_rekomendasi)
                    <span class="badge bg-warning text-dark me-1">
                        <i class="bi bi-star-fill me-1"></i> Favorite
                    </span>
                @endif

                @if (!$menu->is_tersedia)
                    <span class="badge bg-danger">Out of Stock</span>
                @endif
            </div>

            {{-- Menu Title --}}
            <h1 class="display-4 fw-bold mb-2">{{ $menu->nama_menu }}</h1>
            
            {{-- Price --}}
            <div class="harga fs-2 fw-bold text-success mb-4">
                Rp {{ number_format($menu->harga, 0, ',', '.') }}
            </div>
            
            {{-- Description --}}
            <h5 class="fw-bold text-dark mb-2">Description</h5>
            <p class="lead fs-6 text-secondary mb-4" style="line-height: 1.8;">
                {{ $menu->deskripsi ?? 'No complete description available for this menu yet. However, we guarantee its delicious taste!' }}
            </p>

            <hr class="my-4 opacity-10">

            {{-- FORM ADD TO CART --}}
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                
                <div class="d-flex align-items-center gap-3">
                    {{-- Quantity Input --}}
                    <div class="input-group" style="width: 150px;">
                        <span class="input-group-text bg-light border-0 fw-bold text-muted">Qty</span>
                        <input type="number" name="quantity" value="1" min="1" 
                               class="form-control text-center border-light bg-light fw-bold fs-5"
                               {{ !$menu->is_tersedia ? 'disabled' : '' }}>
                    </div>

                    {{-- Order Button (Check Stock First) --}}
                    @if($menu->is_tersedia)
                        <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold shadow-sm flex-grow-1 rounded-3">
                            <i class="bi bi-cart-plus-fill me-2"></i> Order Now
                        </button>
                    @else
                        <button type="button" disabled class="btn btn-secondary btn-lg px-5 fw-bold flex-grow-1 rounded-3">
                            <i class="bi bi-x-circle me-2"></i> Out of Stock
                        </button>
                    @endif
                </div>
            </form>

            {{-- Back Button --}}
            <div class="mt-5 pt-3 border-top">
                <a href="{{ route('menu.indexPage') }}" class="text-decoration-none text-muted fw-bold small">
                    <i class="bi bi-arrow-left me-1"></i> BACK TO MENU LIST
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
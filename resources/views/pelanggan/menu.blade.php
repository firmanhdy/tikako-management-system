@extends('layouts.pelanggan')

@section('title', 'Menu List - Tikako')

@section('content')

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold display-5">Menu List</h1>
        <p class="text-muted">Discover the best flavors from Tikako's kitchen.</p>
        
        {{-- Category Filter Buttons --}}
        <div class="d-flex justify-content-center gap-2 flex-wrap mt-4">
            <a href="{{ route('menu.indexPage') }}" 
               class="btn {{ $kategori_aktif == '' ? 'btn-dark' : 'btn-outline-dark' }} px-4">
                All
            </a>
            <a href="{{ route('menu.indexPage', ['kategori' => 'Makanan']) }}" 
               class="btn {{ $kategori_aktif == 'Makanan' ? 'btn-dark' : 'btn-outline-dark' }} px-4">
                Food
            </a>
            <a href="{{ route('menu.indexPage', ['kategori' => 'Minuman']) }}" 
               class="btn {{ $kategori_aktif == 'Minuman' ? 'btn-dark' : 'btn-outline-dark' }} px-4">
                Drinks
            </a>
            <a href="{{ route('menu.indexPage', ['kategori' => 'Cemilan']) }}" 
               class="btn {{ $kategori_aktif == 'Cemilan' ? 'btn-dark' : 'btn-outline-dark' }} px-4">
                Snacks
            </a>
        </div>
    </div>

    {{-- ======================================================= --}}
    {{-- LOGIC: GROUP MODE (FOR "ALL" FILTER)                    --}}
    {{-- ======================================================= --}}
    @if($mode_tampilan == 'group')
        
        @foreach($menus_grouped as $kategori => $items)
            {{-- Category Header --}}
            <div class="mb-4 mt-5">
                <h3 class="fw-bold border-start border-4 border-warning ps-3">{{ $kategori }}</h3>
            </div>

            <div class="row g-4">
                @foreach($items as $menu)
                    {{-- MENU CARD --}}
                    <div class="col-md-3 col-sm-6">
                        <div class="card h-100 border-0 shadow-sm hover-card overflow-hidden">
                            
                            <div class="position-relative">
                                {{-- Detail Link --}}
                                <a href="{{ route('menu.show', $menu->id) }}">
                                    @if($menu->foto)
                                        <img src="{{ asset('storage/' . $menu->foto) }}" class="card-img-top" alt="{{ $menu->nama_menu }}" 
                                             style="height: 200px; object-fit: cover;">
                                    @else
                                    <img src="https://placehold.co/300x200?text=Menu" class="card-img-top" alt="No Photo" style="height: 200px; object-fit: cover;">
                                    @endif
                                </a>

                                {{-- Favorite Badge --}}
                                @if($menu->is_rekomendasi)
                                    <span class="position-absolute top-0 end-0 bg-warning text-dark badge m-2 shadow-sm">
                                        <i class="bi bi-star-fill"></i> Favorite
                                    </span>
                                @endif
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold mb-1 text-truncate">{{ $menu->nama_menu }}</h5>
                                <p class="card-text text-muted small mb-3 text-truncate">
                                    {{ $menu->deskripsi ?? 'Delicious and flavorful.' }}
                                </p>
                                
                                {{-- Price & Order Button --}}
                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <span class="text-success fw-bold fs-5">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                                    
                                    <form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center">
                                        @csrf
                                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                        {{-- Small Quantity Input --}}
                                        <input type="number" name="quantity" value="1" min="1" 
                                               class="form-control form-control-sm me-2 text-center border-secondary" 
                                               style="width: 50px;">
                                        {{-- Order Button --}}
                                        <button type="submit" class="btn btn-primary btn-sm fw-bold text-nowrap">
                                            + Order
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- END MENU CARD --}}
                @endforeach
            </div>
        @endforeach

        @if($menus_grouped->isEmpty())
            <div class="text-center py-5 text-muted">No menu items available at the moment.</div>
        @endif


    {{-- ======================================================= --}}
    {{-- LOGIC: LIST MODE (FOR SPECIFIC FILTER)                  --}}
    {{-- ======================================================= --}}
    @else

        <div class="mb-4 mt-5">
            <h3 class="fw-bold border-start border-4 border-warning ps-3">{{ $kategori_aktif }}</h3>
        </div>
        
        <div class="row g-4">
            @forelse($menus as $menu)
                {{-- MENU CARD (Repeated Logic for consistency) --}}
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 border-0 shadow-sm hover-card overflow-hidden">
                        
                        <div class="position-relative">
                            <a href="{{ route('menu.show', $menu->id) }}">
                                @if($menu->foto)
                                    <img src="{{ asset('storage/' . $menu->foto) }}" class="card-img-top" alt="{{ $menu->nama_menu }}" 
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="No Photo" 
                                         style="height: 200px; object-fit: cover;">
                                @endif
                            </a>

                            @if($menu->is_rekomendasi)
                                <span class="position-absolute top-0 end-0 bg-warning text-dark badge m-2 shadow-sm">
                                    <i class="bi bi-star-fill"></i> Favorite
                                </span>
                            @endif
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold mb-1 text-truncate">{{ $menu->nama_menu }}</h5>
                            <p class="card-text text-muted small mb-3 text-truncate">
                                {{ $menu->deskripsi ?? 'Delicious and flavorful.' }}
                            </p>
                            
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="text-success fw-bold fs-5">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                                
                                <form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                    <input type="number" name="quantity" value="1" min="1" 
                                           class="form-control form-control-sm me-2 text-center border-secondary" 
                                           style="width: 50px;">
                                    <button type="submit" class="btn btn-primary btn-sm fw-bold text-nowrap">
                                        + Order
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- END MENU CARD --}}
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">No menu items found for this category.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination only appears in list mode --}}
        <div class="d-flex justify-content-center mt-5">
            {{ $menus->appends(['kategori' => $kategori_aktif])->links() }}
        </div>

    @endif

</div>

{{-- Additional CSS Styles --}}
<style>
    .hover-card { transition: transform 0.2s, box-shadow 0.2s; }
    .hover-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>

@endsection
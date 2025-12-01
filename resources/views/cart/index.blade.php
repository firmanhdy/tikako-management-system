@extends('layouts.pelanggan')

@section('title', 'Keranjang Belanja - Tikako')

@section('content')

    <h1 class="text-center mb-4">Keranjang Belanja Anda</h1>

    @if($cartItems->count() > 0)
    <div class="row">
        
        {{-- KOLOM KIRI: DAFTAR ITEM --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4" style="width: 40%;">Menu</th>
                                    <th style="width: 20%;">Harga</th>
                                    <th style="width: 25%;">Jumlah</th>
                                    <th class="text-end pe-4" style="width: 15%;">Total</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($cartItems as $item)
                                    @php $total += $item->menu->harga * $item->quantity; @endphp
                                    
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                @if($item->menu->foto)
                                                    <img src="{{ asset('storage/' . $item->menu->foto) }}" alt="{{ $item->menu->nama_menu }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <img src="https://placehold.co/60?text=Tikako" class="rounded me-3" style="width: 60px; height: 60px;">
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $item->menu->nama_menu }}</div>
                                                    <small class="text-muted">{{ $item->menu->kategori }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td>Rp {{ number_format($item->menu->harga, 0, ',', '.') }}</td>
                                        
                                        <td>
                                           <div class="badge bg-light text-dark border px-3 py-2" style="font-size: 0.9rem;">
                                                {{ $item->quantity }} Porsi
                                           </div>
                                        </td>

                                        <td class="text-end pe-4 fw-bold text-success">
                                            Rp {{ number_format($item->menu->harga * $item->quantity, 0, ',', '.') }}
                                        </td>

                                        <td class="text-center">
                                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0" title="Hapus Item" onclick="return confirm('Hapus item ini?');">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-3">
                    <a href="{{ url('/') }}" class="text-decoration-none text-muted small">
                        <i class="bi bi-arrow-left me-1"></i> Lanjut Belanja Menu Lain
                    </a>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: RINGKASAN & CHECKOUT --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 80px;">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Item</span>
                        <span class="fw-bold">{{ $cartItems->count() }} Menu</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="text-muted">Total Harga</span>
                        <span class="fw-bold fs-4 text-success">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <hr class="opacity-25">

                    {{-- Form Checkout --}}
                    {{-- !! PERBAIKAN 1: Route harus 'cart.checkout' bukan 'checkout' !! --}}
                    <form action="{{ url('cart.checkout') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nomor Meja Anda</label>
                            
                            {{-- !! PERBAIKAN 2: Value Input (Cek kedua jenis session agar aman) !! --}}
                            <input type="text" 
                                   name="nomor_meja" 
                                   class="form-control form-control-lg bg-light" 
                                   placeholder="Cth: 12" 
                                   value="{{ session('nomor_meja_otomatis') ?? session('nomor_meja') }}" 
                                   required>
                            
                            @if(session('nomor_meja_otomatis') || session('nomor_meja'))
                                <div class="form-text text-success fw-bold">
                                    <i class="bi bi-check-circle-fill"></i> Terisi otomatis dari Scan QR!
                                </div>
                            @else
                                <div class="form-text text-muted small">Lihat stiker nomor di meja Anda.</div>
                            @endif
                        </div>
                        
                        <div class="mb-4">
                             <label for="note" class="form-label fw-semibold small">Catatan Pesanan (Opsional)</label>
                             <textarea name="note" id="note" rows="2" class="form-control bg-light" placeholder="Cth: Nasi goreng tidak pedas, Es teh sedikit gula..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-3 fw-bold shadow-sm">
                            Lanjut ke Pembayaran <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="alert alert-info d-flex align-items-start mt-3 shadow-sm border-0" role="alert">
                <i class="bi bi-info-circle-fill fs-5 me-3"></i>
                <div class="small lh-sm">
                    Pesanan akan langsung masuk ke dapur setelah Anda menekan tombol pembayaran.
                </div>
            </div>
        </div>

    </div>
    @else
        {{-- Tampilan Jika Keranjang Kosong --}}
        <div class="text-center py-5">
            <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" alt="Empty Cart" style="width: 150px; opacity: 0.5;">
            <h4 class="mt-4 text-muted fw-bold">Keranjang Masih Kosong</h4>
            <p class="text-secondary">Yuk, pesan makanan enak sekarang!</p>
            <a href="{{ route('menu.indexPage') }}" class="btn btn-primary mt-2 px-4 rounded-pill">Lihat Menu</a>
        </div>
    @endif
</div>

@endsection
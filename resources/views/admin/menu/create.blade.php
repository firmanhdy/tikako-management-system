@extends('layouts.admin')

@section('title', 'Tambah Menu Baru - Tikako')

@section('content')

    <h1 class="display-6 fw-bold mb-4">Tambah Menu Baru</h1>
    
    <div class="card shadow-sm">
        <div class="card-body">
            
            <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    {{-- Left Column: Basic Info --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama_menu" class="form-label">Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="nama_menu" 
                                   class="form-control @error('nama_menu') is-invalid @enderror" 
                                   id="nama_menu" 
                                   value="{{ old('nama_menu') }}"
                                   required>
                            @error('nama_menu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga (Rupiah) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="harga" 
                                   class="form-control @error('harga') is-invalid @enderror" 
                                   id="harga" 
                                   value="{{ old('harga') }}"
                                   required>
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select id="kategori" name="kategori" class="form-select">
                                <option value="Makanan">Makanan</option>
                                <option value="Minuman">Minuman</option>
                                <option value="Cemilan">Cemilan</option>
                            </select>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="hidden" name="is_rekomendasi" value="0">
                            <input type="checkbox" name="is_rekomendasi" value="1" class="form-check-input" id="is_rekomendasi">
                            <label class="form-check-label" for="is_rekomendasi">Jadikan Menu Rekomendasi</label>
                        </div>
                    </div>

                    {{-- Right Column: Details & Media --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
                            <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4">{{ old('deskripsi') }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto Menu (Opsional)</label>
                            <input type="file" 
                                   name="foto" 
                                   class="form-control @error('foto') is-invalid @enderror" 
                                   id="foto">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="is_tersedia" class="form-label">Ketersediaan Stok</label>
                            <select id="is_tersedia" name="is_tersedia" class="form-select">
                                <option value="1">Tersedia</option>
                                <option value="0">Habis</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                
                {{-- Action Buttons --}}
                <div class="d-flex justify-content-start">
                    <button type="submit" class="btn btn-primary me-2">Simpan Menu Baru</button>
                    <a href="{{ route('menu.index') }}" class="btn btn-secondary">Batal</a>
                </div>

            </form>
        </div>
    </div>
@endsection
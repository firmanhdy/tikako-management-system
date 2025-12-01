@extends('layouts.admin')

@section('title', 'Menu Management - Tikako')

@section('content')

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">Menu Management</h1>
            <p class="text-muted mb-0">Manage the menu list displayed on the customer page.</p>
        </div>
        <a href="{{ route('menu.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Menu
        </a>
    </div>

    {{-- Menu Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr>
                            <th class="ps-4" style="width: 5%;">ID</th>
                            <th style="width: 15%;">Photo</th>
                            <th style="width: 25%;">Menu Details</th>
                            <th style="width: 15%;">Price</th>
                            <th style="width: 15%;">Status</th>
                            <th class="text-end pe-4" style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($menus as $item)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">#{{ $item->id }}</td>
                                
                                {{-- Column: Photo --}}
                                <td>
                                    @if ($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" 
                                             alt="{{ $item->nama_menu }}" 
                                             class="rounded shadow-sm border" 
                                             style="width: 70px; height: 70px; object-fit: cover;">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted border" 
                                             style="width: 70px; height: 70px;">
                                            <i class="bi bi-image fs-4"></i>
                                        </div>
                                    @endif
                                </td>

                                {{-- Column: Details (Name & Category) --}}
                                <td>
                                    <div class="fw-bold text-dark fs-6">{{ $item->nama_menu }}</div>
                                    <span class="badge bg-light text-dark border mt-1">
                                        {{ $item->kategori }}
                                    </span>
                                    @if($item->is_rekomendasi)
                                        <span class="badge bg-warning text-dark border mt-1 ms-1">Recommended</span>
                                    @endif
                                </td>

                                <td class="fw-bold text-primary">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </td>

                                {{-- Column: Status (AJAX Dropdown) --}}
                                <td>
                                    <select class="form-select form-select-sm" 
                                            onchange="updateStatusViaDropdown(this)" 
                                            data-id="{{ $item->id }}"
                                            style="min-width: 100px;">
                                        
                                        <option value="1" 
                                                {{ $item->is_tersedia ? 'selected' : '' }} 
                                                class="text-success">
                                            Available
                                        </option>
                                        
                                        <option value="0" 
                                                {{ !$item->is_tersedia ? 'selected' : '' }} 
                                                class="text-danger">
                                            Out of Stock
                                        </option>
                                    </select>
                                </td>

                                {{-- Column: Actions --}}
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('menu.edit', $item->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit Menu">
                                            <i class="bi bi-pencil-square"></i>
                                        </a> 
                                        
                                        <form action="{{ route('menu.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $item->nama_menu }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Menu">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" width="80" class="mb-3 opacity-50" alt="Empty">
                                    <br>
                                    No menu items found. <br>
                                    <a href="{{ route('menu.create') }}" class="text-decoration-none">Add a menu item now</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Pagination --}}
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-end">
                {{ $menus->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // FIX: Menggunakan injeksi langsung Blade token, bukan mencari meta tag di DOM
            // Ini mencegah error "Cannot read properties of null" jika meta tag tidak ada.
            const csrfToken = '{{ csrf_token() }}';

            // Global function to handle status change
            window.updateStatusViaDropdown = function(selectElement) {
                const menuId = selectElement.dataset.id;
                const newStatus = selectElement.value; // 1 or 0
                
                // Construct URL using Blade placeholder replacement technique
                const url = `{{ route('menu.toggle-status', ['menu' => ':menuId']) }}`.replace(':menuId', menuId);
                
                // Visual feedback: Disable and show valid state
                selectElement.classList.add('is-valid');
                selectElement.disabled = true;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken 
                    },
                    body: JSON.stringify({ status: newStatus }) 
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(data.message);
                        // Optional: Show toast notification here
                    } else {
                        alert('Failed to update status: ' + (data.message || 'Unknown Error'));
                        // Revert value on failure
                        selectElement.value = newStatus == 1 ? 0 : 1;
                    }
                })
                .catch(error => {
                    console.error('AJAX Error:', error);
                    alert('Connection error! Please check your network or CSRF token.');
                    selectElement.value = newStatus == 1 ? 0 : 1;
                })
                .finally(() => {
                    // Cleanup visual feedback
                    selectElement.classList.remove('is-valid');
                    selectElement.disabled = false;
                });
            }
        });
    </script>
    @endpush

@endsection
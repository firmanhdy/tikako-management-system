@extends('layouts.admin')

@section('title', 'Customer Data - Tikako')

@section('content')

    {{-- Page Header --}}
    <div class="row mb-4 align-items-end">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold">Customer Data</h1>
            <p class="text-muted mb-0">Manage customer accounts registered in the system.</p>
        </div>
        
        {{-- Total Stats Card --}}
        <div class="col-md-4">
            <div class="card border-0 bg-primary text-white shadow-sm">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-0 text-uppercase small opacity-75">Total Customers</h6>
                        <h2 class="fw-bold mb-0">{{ $customers->total() }}</h2>
                    </div>
                    <i class="bi bi-people-fill fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Customer Data Table --}}
    <div class="card shadow-sm border-0">
        
        {{-- Search Form --}}
        <div class="card-header bg-white py-3">
            <form action="{{ route('admin.customers.index') }}" method="GET">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" 
                           name="search" 
                           class="form-control border-start-0 bg-light" 
                           placeholder="Search by name or email..." 
                           value="{{ request('search') }}">
                    
                    @if(request('search'))
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary" title="Reset Search">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0"> 
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4" style="width: 5%;">No</th>
                            <th style="width: 35%;">Customer Profile</th>
                            <th style="width: 10%;">Role</th>
                            <th style="width: 30%;">Joined Date</th>
                            <th class="text-end pe-4" style="width: 10%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $index => $user)
                            <tr>
                                <td class="ps-4 text-muted">{{ $customers->firstItem() + $index }}</td>
                                
                                {{-- Customer Profile Column --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        {{-- Avatar Initials --}}
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold me-3 shadow-sm flex-shrink-0" 
                                             style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        
                                        <div class="overflow-hidden">
                                            <div class="fw-bold text-dark text-truncate" style="max-width: 150px;">
                                                {{ $user->name }}
                                            </div>
                                            <div class="small text-muted text-truncate" style="max-width: 150px;">
                                                {{ $user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark border rounded-pill">User</span>
                                </td>

                                <td class="text-nowrap">
                                    <div class="fw-bold text-dark">{{ $user->created_at->format('d M Y') }}</div>
                                    <div class="small text-muted">{{ $user->created_at->diffForHumans() }}</div>
                                </td>

                                <td class="text-end pe-4">
                                    <form action="{{ route('admin.customers.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger border-0" 
                                                title="Delete Customer" 
                                                {{ Auth::id() === $user->id ? 'disabled' : '' }}>
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-1 opacity-50 mb-2 d-block"></i>
                                    No customers found.
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
                {{ $customers->links() }}
            </div>
        </div>

    </div> 

@endsection
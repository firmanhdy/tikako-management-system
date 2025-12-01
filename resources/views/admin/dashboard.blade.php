@extends('layouts.admin')

@section('title', 'Admin Dashboard - Tikako')

@section('content')

    {{-- Page Header --}}
    <h1 class="display-5 fw-bold mb-4">Main Dashboard</h1>
    <p class="text-muted">Operational summary and current performance overview.</p>

    {{-- STATISTICS SECTION (KPI) --}}
    <div class="row g-3 mb-5">
        
        {{-- Total Orders --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title text-uppercase small text-muted">Total Orders</h5>
                    <div class="display-4 fw-bold text-primary">{{ $totalOrders }}</div>
                </div>
            </div>
        </div>

        {{-- Awaiting Kitchen --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title text-uppercase small text-muted">Awaiting Kitchen</h5>
                    <div class="display-4 fw-bold text-warning">{{ $ordersAwaiting }}</div>
                </div>
            </div>
        </div>

        {{-- Net Revenue --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title text-uppercase small text-muted">Net Revenue</h5>
                    <div class="display-6 fw-bold text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        {{-- Completion Rate --}}
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-uppercase small text-muted">Completion Rate</h5>
                    <div class="display-4 fw-bold text-info">{{ $efficiency }}%</div>
                    <small class="text-muted" style="font-size: 0.7rem;">Completed / Total Orders</small>
                </div>
            </div>
        </div>

    </div>

    {{-- LIVE MONITORING TABLE --}}
    <div class="card shadow-sm mt-5">
        <div class="card-header bg-white fw-bold border-bottom-0 py-3 d-flex justify-content-between align-items-center">
            <span><i class="bi bi-activity me-2"></i>Recent Order Activity (Live)</span>
            <span class="badge bg-danger animate-pulse">Live Monitoring</span>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Table</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Time</th>
                    </tr>
                </thead>
                
                {{-- ID for Auto Refresh Logic --}}
                <tbody id="dashboard-data">
                    @forelse ($latestOrders as $order)
                        @php
                            $statusClass = '';
                            $badgeColor = 'bg-secondary';
                            
                            // Map database status to badge colors
                            if ($order->status == 'Diterima') $badgeColor = 'bg-warning text-dark';
                            elseif ($order->status == 'Sedang Dimasak') $badgeColor = 'bg-primary';
                            elseif ($order->status == 'Selesai') $badgeColor = 'bg-success';
                        @endphp
                        <tr>
                            <td>#{{ $order->id }}</td>
                            
                            <td class="fw-bold text-primary">
                                {{ $order->user->name ?? 'Guest' }}
                            </td>

                            <td class="fw-bold text-center">{{ $order->nomor_meja }}</td>
                            
                            {{-- Item Details --}}
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @foreach ($order->details as $detail)
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-dark rounded-circle me-2" style="width: 20px; height: 20px; padding: 0; display: flex; align-items: center; justify-content: center; font-size: 10px;">
                                                {{ $detail->quantity }}
                                            </span>
                                            <small class="text-muted">{{ $detail->menu->nama_menu }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            
                            <td class="fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            
                            <td><span class="badge {{ $badgeColor }} rounded-pill">{{ $order->status }}</span></td>
                            
                            <td class="small text-muted">{{ $order->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No active orders at the moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white text-center py-3">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">View All Orders</a>
        </div>
    </div>

    {{-- Auto Refresh Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // FIX: Cek apakah halaman berjalan di lingkungan Blob/Preview
            // Jika ya, hentikan auto-refresh untuk mencegah error console "Failed to fetch"
            if (window.location.protocol === 'blob:' || window.location.protocol === 'about:') {
                console.warn('Auto-refresh disabled in preview mode to prevent fetch errors.');
                return;
            }

            // Refresh dashboard data every 15 seconds
            const intervalId = setInterval(function () {
                updateDashboard();
            }, 15000); 

            function updateDashboard() {
                fetch(window.location.href)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(html => {
                        var parser = new DOMParser();
                        var doc = parser.parseFromString(html, 'text/html');
                        
                        // Update Table Body
                        var newTable = doc.getElementById('dashboard-data').innerHTML;
                        if(newTable) {
                            document.getElementById('dashboard-data').innerHTML = newTable;
                            console.log('Dashboard updated: ' + new Date().toLocaleTimeString());
                        }
                    })
                    .catch(error => {
                        console.error('Dashboard refresh paused (Error):', error);
                        // Optional: Stop polling on error to prevent spamming
                        // clearInterval(intervalId);
                    });
            }
        });
    </script>
@endsection
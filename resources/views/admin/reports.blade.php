@extends('layouts.admin')

@section('title', 'Sales Report - Tikako')

@section('content')

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">Sales Report</h1>
            <p class="text-muted mb-0">Revenue summary and list of completed transactions.</p>
        </div>
        <div class="mt-3 mt-md-0">
            {{-- Print Button Dropdown --}}
            <div class="btn-group shadow-sm">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-printer-fill me-2"></i> Print Report
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header">Select Period</h6></li>
                    
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.reports.print', ['period' => '7_days']) }}" target="_blank">
                            Last 7 Days
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.reports.print', ['period' => '30_days']) }}" target="_blank">
                            Last 30 Days
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.reports.print', ['period' => 'this_month']) }}" target="_blank">
                            This Month
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('admin.reports.print', ['period' => 'all']) }}" target="_blank">
                            All History
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    {{-- SUMMARY & CHART SECTION --}}
    <div class="row mb-4">
        {{-- Revenue Summary Card --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h6 class="text-uppercase opacity-75 mb-2">Total Net Revenue</h6>
                        <h2 class="display-5 fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                    </div>
                    <div class="mt-3 pt-3 border-top border-white border-opacity-25">
                        <small class="opacity-75"><i class="bi bi-info-circle me-1"></i> Based on {{ $completedOrders->count() }} completed transactions.</small>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Sales Chart --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100 bg-white">
                
                <div class="card-header bg-white border-0 pb-0 pt-3 d-flex justify-content-between align-items-center">
                    <h6 class="text-uppercase text-muted mb-0 small fw-bold">
                        <i class="bi bi-graph-up me-1"></i> Sales Trend ({{ $titleChart }})
                    </h6>
                    
                    {{-- Period Filter Buttons --}}
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('admin.reports.index', ['period' => '7_days']) }}" 
                           class="btn {{ $currentPeriod == '7_days' ? 'btn-dark' : 'btn-outline-light text-secondary' }}">
                            7 Days
                        </a>
                        <a href="{{ route('admin.reports.index', ['period' => '30_days']) }}" 
                           class="btn {{ $currentPeriod == '30_days' ? 'btn-dark' : 'btn-outline-light text-secondary' }}">
                            30 Days
                        </a>
                        <a href="{{ route('admin.reports.index', ['period' => 'this_month']) }}" 
                           class="btn {{ $currentPeriod == 'this_month' ? 'btn-dark' : 'btn-outline-light text-secondary' }}">
                            This Month
                        </a>
                    </div>
                </div>

                <div class="card-body pt-2">
                    <canvas id="salesChart" style="max-height: 220px;"></canvas> 
                </div>
            </div>
        </div>
    </div>

    {{-- TRANSACTION HISTORY TABLE --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-receipt me-2"></i>Transaction History</h5>
            
            {{-- Search Form --}}
            <form action="{{ route('admin.reports.index') }}" method="GET" style="width: 250px;">
                
                {{-- IMPORTANT: Preserve current period filter during search --}}
                <input type="hidden" name="period" value="{{ $currentPeriod }}">
                
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white text-muted border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" 
                           name="search" 
                           class="form-control border-start-0 ps-0" 
                           placeholder="Search ID, Table, or Name..." 
                           value="{{ request('search') }}"> 
                           
                    @if(request('search'))
                        {{-- Reset Search Button --}}
                        <a href="{{ route('admin.reports.index', ['period' => $currentPeriod]) }}" 
                           class="btn btn-outline-secondary border-start-0" 
                           title="Clear Search">
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
                            <th class="ps-4">Order ID</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Table</th>
                            <th>Order Details</th>
                            <th class="text-end pe-4">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($completedOrders as $order)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">#{{ $order->id }}</td>
                                
                                <td>
                                    <div class="small fw-bold text-dark">{{ $order->created_at->format('d M Y') }}</div>
                                    <div class="small text-muted">{{ $order->created_at->format('H:i') }} WIB</div>
                                </td>
                                
                                <td>
                                    @if($order->user)
                                        {{ $order->user->name }}
                                    @else
                                        <span class="text-muted fst-italic">Guest</span>
                                    @endif
                                </td>

                                <td><span class="badge bg-light text-dark border">{{ $order->nomor_meja }}</span></td>

                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @foreach ($order->details as $detail)
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-secondary rounded-circle me-2" 
                                                    style="width: 20px; height: 20px; padding: 0; display: flex; align-items: center; justify-content: center; font-size: 10px;">
                                                    {{ $detail->quantity }}
                                                </span>
                                                <span class="small text-dark">{{ $detail->menu->nama_menu }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>

                                <td class="text-end pe-4 fw-bold text-success">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-clipboard-x fs-1 opacity-50 d-block mb-2"></i>
                                    No completed sales data found for this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Pagination (Assuming $completedOrders is a collection, not paginated) --}}
        {{-- If $completedOrders were paginated, you would use: {{ $completedOrders->links() }} --}}
        {{-- If you wish to paginate the results, update AdminOrderController to use paginate(X) --}}
    </div>
    
    {{-- Chart.js Library and Initialization --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            // Data injected from PHP Controller (FIXED: Using fallback '[]' to ensure valid JS syntax)
            const labels = {!! json_encode($chartLabels) ?: '[]' !!};
            const dataValues = {!! json_encode($chartValues) ?: '[]' !!};

            new Chart(ctx, {
                type: 'line', 
                data: {
                    labels: labels, 
                    datasets: [{
                        label: 'Revenue (Rp)',
                        data: dataValues, 
                        borderColor: '#198754', // Bootstrap Green (Success)
                        backgroundColor: 'rgba(25, 135, 84, 0.1)', 
                        borderWidth: 2,
                        tension: 0.4, 
                        fill: true, 
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#198754',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }, 
                        tooltip: {
                            callbacks: {
                                // Custom tooltip format for Rupiah
                                label: function(context) {
                                    let value = context.raw;
                                    return ' Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [2, 4] }, 
                            ticks: { display: false } // Hide Y-axis ticks for cleaner look
                        },
                        x: {
                            grid: { display: false } 
                        }
                    }
                }
            });
        });
    </script>
@endsection
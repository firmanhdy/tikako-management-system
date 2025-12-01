@extends('layouts.admin')

@section('title', 'Order Management - Tikako')

@section('content')
    
    {{-- Real-time Order Monitor Component (Vue/JS) --}}
    <order-monitor></order-monitor> 
    
    {{-- Page Header --}}
    <h1 class="display-6 fw-bold mb-4">Order Management</h1>
    <p class="text-muted">List of all incoming orders. Newest orders appear at the top.</p>

    {{-- Orders Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0"> 
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th> 
                        <th>Table</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Time</th>
                        <th style="width: 25%;">Details</th>
                        <th style="width: 20%;">Actions</th>
                    </tr>
                </thead>
                <tbody id="order-data">
                    @forelse ($orders as $order) {{-- Variable changed to $orders to match Controller --}}
                        @php
                            $statusClass = ''; 
                            $badgeColor = 'bg-secondary';

                            // Status Color Logic (Based on KPI)
                            if ($order->status == 'Diterima') {
                                $statusClass = 'status-diterima'; 
                                $badgeColor = 'bg-warning text-dark';
                            } elseif ($order->status == 'Sedang Dimasak') {
                                $statusClass = 'status-sedang-dimasak';
                                $badgeColor = 'bg-primary';
                            } elseif ($order->status == 'Selesai') {
                                $statusClass = 'status-selesai';
                                $badgeColor = 'bg-success';
                            } elseif ($order->status == 'Dibatalkan') {
                                $statusClass = 'status-dibatalkan';
                                $badgeColor = 'bg-secondary';
                            }
                        @endphp

                        <tr class="{{ $statusClass }} align-middle">
                            <td>#{{ $order->id }}</td>
                            
                            <td class="fw-bold">{{ $order->user->name ?? 'Guest' }}</td>
                            
                            <td class="text-center fw-bold">{{ $order->nomor_meja }}</td>
                            
                            <td>
                                <span class="badge {{ $badgeColor }} rounded-pill">
                                    {{ $order->status }}
                                </span>
                            </td> 
                            
                            <td class="fw-bold text-success">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                            
                            <td>
                                <div class="small fw-bold">{{ $order->created_at->format('H:i') }}</div>
                                <div class="small text-muted">{{ $order->created_at->diffForHumans() }}</div>
                            </td>
                            
                            {{-- Column: Details & Notes --}}
                            <td>
                                <ul class="list-unstyled small mb-0">
                                    @foreach ($order->details as $detail)
                                        <li class="mb-1">
                                            <strong>{{ $detail->quantity }}x</strong> {{ $detail->menu->nama_menu }}
                                            
                                            {{-- Display Item Note if exists --}}
                                            @if($detail->note)
                                                <div class="text-danger fst-italic" style="font-size: 0.85em;">
                                                    Note: {{ $detail->note }}
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                                
                                {{-- Display Order Note if exists --}}
                                @if($order->note)
                                    <div class="alert alert-warning py-1 px-2 mt-1 mb-0" style="font-size: 0.85em;">
                                        <i class="bi bi-info-circle me-1"></i> <strong>Note:</strong> {{ $order->note }}
                                    </div>
                                @endif
                            </td>

                            {{-- Column: Actions (Update Status + Print) --}}
                            <td>
                                <div class="d-flex flex-column gap-2">
                                    
                                    {{-- 1. Update Status Dropdown --}}
                                    <div class="dropdown w-100">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                            Update Status
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                                                    @csrf <input type="hidden" name="status" value="Diterima">
                                                    <button class="dropdown-item">Diterima (Received)</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                                                    @csrf <input type="hidden" name="status" value="Sedang Dimasak">
                                                    <button class="dropdown-item">Sedang Dimasak (Cooking)</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                                                    @csrf <input type="hidden" name="status" value="Selesai">
                                                    <button class="dropdown-item">Selesai (Completed)</button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                                                    @csrf <input type="hidden" name="status" value="Dibatalkan">
                                                    <button class="dropdown-item text-danger">Dibatalkan (Cancelled)</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    {{-- 2. Print Buttons --}}
                                    <div class="d-flex gap-1">
                                        {{-- Cashier Print (Modal Trigger) --}}
                                        <button type="button" 
                                                class="btn btn-sm btn-light border shadow-sm flex-fill" 
                                                onclick="showPaymentModal({{ $order->id }}, {{ $order->total_price }})">
                                            <i class="bi bi-receipt"></i> Cashier
                                        </button>
                                        
                                        {{-- Kitchen Print (Direct Link) --}}
                                        <a href="{{ route('admin.orders.print', ['order' => $order->id, 'type' => 'dapur']) }}" target="_blank" class="btn btn-sm btn-dark shadow-sm flex-fill">
                                            <i class="bi bi-egg-fried"></i> Kitchen
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                No orders received today.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-end">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    {{-- ================================================= --}}
    {{-- PAYMENT CALCULATOR MODAL                          --}}
    {{-- ================================================= --}}
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light py-2">
                    <h6 class="modal-title fw-bold">Payment Calculator</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="small text-muted">Total Bill</label>
                        <div class="fs-4 fw-bold text-primary" id="modalTotalDisplay">Rp 0</div>
                        <input type="hidden" id="modalTotalValue">
                        <input type="hidden" id="modalOrderId">
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold">Cash Received</label>
                        <input type="number" class="form-control form-control-lg" id="inputBayar" placeholder="0" oninput="hitungKembalian()">
                    </div>
                    <div class="d-flex justify-content-between border-top pt-2">
                        <span class="fw-bold">Change:</span>
                        <span class="fw-bold text-success" id="displayKembalian">Rp 0</span>
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-primary w-100" onclick="prosesCetak()">
                        <i class="bi bi-printer-fill me-1"></i> Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        /**
         * Show the Payment Calculator Modal.
         */
        function showPaymentModal(orderId, totalPrice) {
            document.getElementById('modalOrderId').value = orderId;
            document.getElementById('modalTotalValue').value = totalPrice;
            document.getElementById('modalTotalDisplay').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalPrice);
            document.getElementById('inputBayar').value = ''; 
            document.getElementById('displayKembalian').innerText = 'Rp 0';
            
            var myModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            myModal.show();
            
            // Auto-focus input field
            setTimeout(() => { document.getElementById('inputBayar').focus(); }, 500);
        }

        /**
         * Calculate Change (Kembalian) dynamically.
         */
        function hitungKembalian() {
            let total = parseInt(document.getElementById('modalTotalValue').value);
            let bayar = parseInt(document.getElementById('inputBayar').value) || 0;
            let kembali = bayar - total;
            
            let displayEl = document.getElementById('displayKembalian');
            
            if (kembali < 0) {
                displayEl.innerText = '- Rp ' + new Intl.NumberFormat('id-ID').format(Math.abs(kembali)) + ' (Insufficient)';
                displayEl.classList.add('text-danger');
                displayEl.classList.remove('text-success');
            } else {
                displayEl.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(kembali);
                displayEl.classList.remove('text-danger');
                displayEl.classList.add('text-success');
            }
        }

        /**
         * Process Print Request (Open new tab).
         */
        function prosesCetak() {
            let orderId = document.getElementById('modalOrderId').value;
            let bayar = document.getElementById('inputBayar').value;
            let total = document.getElementById('modalTotalValue').value;
            let kembali = bayar - total;

            if (!bayar || bayar < 1) {
                bayar = total; 
                kembali = 0;
            }

            // Open print route in new tab
            let url = `/admin/orders/${orderId}/print/kasir?bayar=${bayar}&kembali=${kembali}`;
            window.open(url, '_blank');
        }
    </script>
    @endpush

@endsection
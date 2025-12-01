<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ApiOrderController extends Controller
{
    /**
     * API Endpoint for Real-time Order Polling.
     * Typically called by JavaScript (AJAX/Fetch) in the admin dashboard
     * to trigger notifications without page reload.
     */
    public function latestOrders()
    {
        // Retrieve the latest order by ID (descending)
        $latestOrder = Order::latest('id')->first();

        // Count orders with 'Diterima' status (Pending kitchen processing)
        $newOrdersCount = Order::where('status', 'Diterima')->count();        

        return response()->json([
            'status' => 'success',
            'latest_order' => $latestOrder,
            'new_count' => $newOrdersCount,
        ]);
    }
}
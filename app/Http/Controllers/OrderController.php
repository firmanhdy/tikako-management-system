<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Display success page after checkout.
     */
    public function success()
    {
        // Retrieve orderId from session (flash data)
        $orderId = Session::get('orderId');

        if (!$orderId) {
            return to_route('home'); // Redirect to home if accessed directly
        }

        return view('order.success', compact('orderId'));
    }

    /**
     * Display user's order history.
     */
    public function myOrders()
    {
        $user = Auth::user();

        if (!$user) {
            return to_route('login');
        }

        $orders = Order::with('details.menu')
            ->where('user_id', $user->id)
            ->latest() // Shortcut for orderBy('created_at', 'desc')
            ->get();

        return view('pelanggan.pesanan', compact('orders'));
    }

    /**
     * Repeat Order functionality.
     * Re-adds items from a past order to the cart.
     */
    public function repeatOrder(Order $order)
    {
        $userId = Auth::id();

        // Security Check: Ensure user can only repeat their own orders
        if ($order->user_id !== $userId) {
            return to_route('orders.myOrders')->with('error', 'Unauthorized action.');
        }

        $outOfStockItems = [];

        foreach ($order->details as $detail) {
            $menu = $detail->menu;

            // Skip if menu is deleted or unavailable
            if (!$menu || !$menu->is_tersedia) {
                $outOfStockItems[] = $menu ? $menu->nama_menu : 'Unknown Item';
                continue;
            }

            // Find item in cart, or initialize new instance if not exists
            $cartItem = CartItem::firstOrNew([
                'user_id' => $userId,
                'menu_id' => $detail->menu_id
            ]);

            // Add quantity
            $cartItem->quantity += $detail->quantity;
            $cartItem->save();
        }

        // Build notification message
        $message = "Order #{$order->id} items added to cart successfully!";
        
        if (!empty($outOfStockItems)) {
            $list = implode(', ', $outOfStockItems);
            $message .= " Note: The following items were skipped due to lack of stock: {$list}.";
        }

        return to_route('cart.index')->with('success', $message);
    }

    /**
     * Show user profile.
     */
    public function profile()
    {
        return view('pelanggan.profile', ['user' => Auth::user()]);
    }

    /**
     * Update user profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'email', 
                'max:255', 
                Rule::unique('users')->ignore($user->id) // Ignore current user's email for unique check
            ],
        ]);

        $user->update($validated);

        return to_route('profile')->with('success', 'Profile updated successfully!');
    }
}
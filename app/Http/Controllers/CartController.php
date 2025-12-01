<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display the user's shopping cart.
     */
    public function index()
    {
        $userId = Auth::id();

        if (!$userId) {
            return to_route('login');
        }

        $cartItems = CartItem::with('menu')
            ->where('user_id', $userId)
            ->get();

        return view('cart.index', compact('cartItems'));
    }

    /**
     * Add an item to the cart.
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menu,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $menu = Menu::find($validated['menu_id']);

        // Check menu availability
        if (!$menu || !$menu->is_tersedia) {
            return back()->with('error', 'Sorry, this menu item is currently unavailable.');
        }

        $userId = Auth::id();

        // Check if item already exists in the cart
        $cartItem = CartItem::where('user_id', $userId)
            ->where('menu_id', $menu->id)
            ->first();

        if ($cartItem) {
            // Update quantity if item exists
            $cartItem->increment('quantity', $validated['quantity']);
        } else {
            // Create new cart entry
            CartItem::create([
                'user_id' => $userId,
                'menu_id' => $menu->id,
                'quantity' => $validated['quantity']
            ]);
        }

        return back()->with('success', 'Menu added to cart successfully!');
    }

    /**
     * Remove an item from the cart.
     */
    public function destroy(CartItem $cartItem)
    {
        // Authorization: Ensure user owns the cart item
        if ($cartItem->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Process checkout using Database Transactions.
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'nomor_meja' => 'required|string|max:50',
            'note' => 'nullable|string'
        ]);

        $userId = Auth::id();
        
        $cartItems = CartItem::with('menu')
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        // Final stock/availability check before processing
        foreach ($cartItems as $item) {
            if (!$item->menu || !$item->menu->is_tersedia) {
                $item->delete(); // Auto-remove invalid items
                return to_route('cart.index')->with(
                    'error', 
                    "Sorry, item '{$item->menu->nama_menu}' is out of stock and has been removed from your cart."
                );
            }
        }

        // Use Database Transaction to ensure data integrity
        try {
            $orderId = DB::transaction(function () use ($request, $userId, $cartItems) {
                
                // Calculate total price
                $totalPrice = $cartItems->sum(fn($item) => $item->menu->harga * $item->quantity);

                // 1. Create Main Order
                $order = Order::create([
                    'user_id' => $userId,
                    'nomor_meja' => $request->nomor_meja,
                    'total_price' => $totalPrice,
                    'status' => 'Diterima',
                    'note' => $request->note
                ]);

                // 2. Migrate Cart Items to Order Details
                foreach ($cartItems as $item) {
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'menu_id' => $item->menu_id,
                        'quantity' => $item->quantity,
                        'price' => $item->menu->harga,
                        'note' => $item->note
                    ]);
                }

                // 3. Clear User's Cart
                CartItem::where('user_id', $userId)->delete();

                return $order->id;
            });

            return to_route('order.success')->with('orderId', $orderId);

        } catch (\Exception $e) {
            // Log::error($e);
            return back()->with('error', 'System error occurred during checkout. Please try again.');
        }
    }

    /**
     * Update item specific notes (e.g., "No spicy").
     */
    public function updateNote(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'note' => 'nullable|string|max:255'
        ]);

        $cartItem->update(['note' => $validated['note']]);

        return back()->with('success', 'Note saved successfully!');
    }
}
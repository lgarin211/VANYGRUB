<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Get cart items for current session or user
     */
    public function index(Request $request)
    {
        $sessionId = $request->session_id ?? session()->getId();
        $userId = Auth::id();

        $query = Cart::with('product');

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $cartItems = $query->get();

        $formattedItems = $cartItems->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product' => [
                    'id' => $item->product->id,
                    'name' => $item->product->name,
                    'image' => $item->product->image ? url('storage/' . $item->product->image) : null,
                    'price' => $item->product->price,
                    'sale_price' => $item->product->sale_price,
                ],
                'quantity' => $item->quantity,
                'price' => $item->price,
                'size' => $item->size,
                'color' => $item->color,
                'total' => $item->total,
                'created_at' => $item->created_at,
            ];
        });

        $subtotal = $cartItems->sum('total');
        $itemCount = $cartItems->sum('quantity');

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $formattedItems,
                'subtotal' => $subtotal,
                'item_count' => $itemCount,
                'session_id' => $sessionId
            ]
        ]);
    }

    /**
     * Add item to cart
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:vany_products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'session_id' => 'nullable|string'
        ]);

        $product = Product::findOrFail($request->product_id);
        $sessionId = $request->session_id ?? session()->getId();
        $userId = Auth::id();

        // Use sale_price if available, otherwise use regular price
        $price = $product->sale_price ?? $product->price;
        $total = $price * $request->quantity;

        // Check if item already exists in cart
        $existingItem = Cart::where('product_id', $request->product_id)
            ->where('size', $request->size)
            ->where('color', $request->color);

        if ($userId) {
            $existingItem->where('user_id', $userId);
        } else {
            $existingItem->where('session_id', $sessionId);
        }

        $existingItem = $existingItem->first();

        if ($existingItem) {
            // Update existing item
            $existingItem->quantity += $request->quantity;
            $existingItem->total = $existingItem->price * $existingItem->quantity;
            $existingItem->save();

            $cartItem = $existingItem;
        } else {
            // Create new cart item
            $cartItem = Cart::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $price,
                'total' => $total,
                'size' => $request->size,
                'color' => $request->color,
            ]);
        }

        // Load product relationship
        $cartItem->load('product');

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully',
            'data' => [
                'id' => $cartItem->id,
                'product_id' => $cartItem->product_id,
                'product' => [
                    'id' => $cartItem->product->id,
                    'name' => $cartItem->product->name,
                    'image' => $cartItem->product->image ? url('storage/' . $cartItem->product->image) : null,
                ],
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
                'size' => $cartItem->size,
                'color' => $cartItem->color,
                'total' => $cartItem->total,
            ]
        ]);
    }

    /**
     * Update cart item
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::findOrFail($id);

        $cartItem->quantity = $request->quantity;
        $cartItem->total = $cartItem->price * $request->quantity;
        $cartItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Cart item updated successfully',
            'data' => [
                'id' => $cartItem->id,
                'quantity' => $cartItem->quantity,
                'total' => $cartItem->total,
            ]
        ]);
    }

    /**
     * Remove item from cart
     */
    public function destroy($id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully'
        ]);
    }

    /**
     * Clear all cart items
     */
    public function clear(Request $request)
    {
        $sessionId = $request->session_id ?? session()->getId();
        $userId = Auth::id();

        $query = Cart::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $query->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }
}

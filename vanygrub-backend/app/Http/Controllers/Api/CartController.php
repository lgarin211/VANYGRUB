<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Get cart items
     */
    public function index(Request $request)
    {
        try {
            $sessionId = $request->query('session_id');
            $userId = $request->user() ? $request->user()->id : null;

            if (!$sessionId && !$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session ID or user authentication required',
                    'data' => []
                ], Response::HTTP_BAD_REQUEST);
            }

            $cartItems = Cart::getCartItems($sessionId, $userId);

            // Calculate totals
            $subtotal = $cartItems->sum('total_price');
            $itemCount = $cartItems->sum('quantity');
            $tax = $subtotal * 0.1; // 10% tax
            $shipping = $cartItems->count() > 0 ? 50000 : 0; // 50k shipping if items exist
            $total = $subtotal + $tax + $shipping;

            // Transform data untuk frontend
            $transformedItems = $cartItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product ? $item->product->name : 'Unknown Product',
                    'price' => $item->formatted_unit_price,
                    'originalPrice' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'color' => $item->color,
                    'size' => $item->size,
                    'total_price' => $item->formatted_total_price,
                    'total_price_raw' => $item->total_price,
                    'image' => $this->getSelectedImage($item),
                    'selected_image' => $item->selected_image,
                    'product' => $item->product ? [
                        'id' => $item->product->id,
                        'name' => $item->product->name,
                        'slug' => $item->product->slug,
                        'main_image' => $item->product->main_image ?
                            'http://vanygroup.id/storage/' . $item->product->main_image : null,
                        'price' => $item->product->price,
                        'in_stock' => $item->product->in_stock,
                    ] : null
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Cart retrieved successfully',
                'data' => [
                    'items' => $transformedItems,
                    'summary' => [
                        'itemCount' => $itemCount,
                        'subtotal' => $subtotal,
                        'subtotal_formatted' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
                        'tax' => $tax,
                        'tax_formatted' => 'Rp ' . number_format($tax, 0, ',', '.'),
                        'shipping' => $shipping,
                        'shipping_formatted' => 'Rp ' . number_format($shipping, 0, ',', '.'),
                        'total' => $total,
                        'total_formatted' => 'Rp ' . number_format($total, 0, ',', '.')
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Cart index error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cart',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Add item to cart
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|integer|exists:vany_products,id',
                'quantity' => 'required|integer|min:1',
                'color' => 'nullable|string|max:100',
                'size' => 'nullable|string|max:50',
                'session_id' => 'nullable|string',
                'selected_image' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $sessionId = $request->session_id;
            $userId = $request->user() ? $request->user()->id : null;

            if (!$sessionId && !$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session ID or user authentication required'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Get product untuk mendapatkan harga
            $product = Product::findOrFail($request->product_id);

            if (!$product->in_stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product is out of stock'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Data untuk cart
            $cartData = [
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'color' => $request->color,
                'size' => $request->size,
                'unit_price' => $product->price,
                'total_price' => $request->quantity * $product->price,
                'selected_image' => $request->selected_image
            ];

            if ($userId) {
                $cartData['user_id'] = $userId;
            } else {
                $cartData['session_id'] = $sessionId;
            }

            $cartItem = Cart::addItem($cartData);

            Log::info('Item added to cart successfully', [
                'cart_item_id' => $cartItem->id,
                'product_id' => $request->product_id,
                'session_id' => $sessionId,
                'user_id' => $userId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully',
                'data' => [
                    'id' => $cartItem->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'color' => $cartItem->color,
                    'size' => $cartItem->size,
                    'unit_price' => $cartItem->unit_price,
                    'total_price' => $cartItem->total_price,
                    'formatted_total_price' => $cartItem->formatted_total_price,
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'main_image' => $product->main_image ? asset('storage/' . $product->main_image) : null
                    ]
                ]
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            Log::error('Cart store error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'quantity' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $cartItem = Cart::findOrFail($id);

            // Verify ownership (session or user)
            $sessionId = $request->query('session_id');
            $userId = $request->user() ? $request->user()->id : null;

            if ($userId && $cartItem->user_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], Response::HTTP_FORBIDDEN);
            }

            if (!$userId && $cartItem->session_id !== $sessionId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], Response::HTTP_FORBIDDEN);
            }

            $cartItem->updateQuantity($request->quantity);

            return response()->json([
                'success' => true,
                'message' => 'Cart item updated successfully',
                'data' => [
                    'id' => $cartItem->id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'total_price' => $cartItem->total_price,
                    'formatted_total_price' => $cartItem->formatted_total_price
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Cart update error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'cart_item_id' => $id,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart item',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove item from cart
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $cartItem = Cart::findOrFail($id);

            // Verify ownership
            $sessionId = $request->query('session_id');
            $userId = $request->user() ? $request->user()->id : null;

            if ($userId && $cartItem->user_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], Response::HTTP_FORBIDDEN);
            }

            if (!$userId && $cartItem->session_id !== $sessionId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], Response::HTTP_FORBIDDEN);
            }

            $cartItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Cart destroy error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'cart_item_id' => $id,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item from cart',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Clear entire cart
     */
    public function clear(Request $request)
    {
        try {
            // Handle both POST (body) and DELETE (query parameter) requests
            $sessionId = $request->input('session_id') ?: $request->query('session_id');
            $userId = $request->user() ? $request->user()->id : null;

            // For DELETE requests, also check the raw input if JSON body is sent
            if (!$sessionId && $request->isMethod('delete')) {
                $data = json_decode($request->getContent(), true);
                $sessionId = $data['session_id'] ?? null;
            }

            if (!$sessionId && !$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session ID or user authentication required'
                ], Response::HTTP_BAD_REQUEST);
            }

            $deleted = Cart::clearCart($sessionId, $userId);

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully',
                'data' => [
                    'deleted_items' => $deleted
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Cart clear error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get the appropriate image for cart item
     */
    private function getSelectedImage($item)
    {
        $baseUrl = 'http://vanygroup.id/storage/';

        // If cart item has a selected image, use it
        if ($item->selected_image) {
            // If it's just a path, construct the full URL with production domain
            if (!str_starts_with($item->selected_image, 'http')) {
                return $baseUrl . $item->selected_image;
            }
            return $item->selected_image;
        }

        // Fall back to product main image
        if ($item->product && $item->product->main_image) {
            return $baseUrl . $item->product->main_image;
        }

        // Final fallback to placeholder
        return '/api/placeholder/300/300';
    }
}

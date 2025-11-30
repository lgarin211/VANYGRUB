<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Get user orders
     */
    public function index(Request $request)
    {
        $sessionId = $request->session_id ?? session()->getId();
        $userId = Auth::id();

        $query = Order::with('orderItems.product');

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $formattedOrders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'customer_phone' => $order->customer_phone,
                'shipping_address' => $order->shipping_address,
                'shipping_city' => $order->shipping_city,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'order_status' => $order->order_status,
                'subtotal' => $order->subtotal,
                'shipping_cost' => $order->shipping_cost,
                'tax_amount' => $order->tax_amount,
                'total_amount' => $order->total_amount,
                'tracking_number' => $order->tracking_number,
                'items' => $order->orderItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product' => [
                            'id' => $item->product->id,
                            'name' => $item->product->name,
                            'image' => $item->product->image ? url('storage/' . $item->product->image) : null,
                        ],
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'size' => $item->size,
                        'color' => $item->color,
                        'total' => $item->total,
                    ];
                }),
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedOrders
        ]);
    }

    /**
     * Create new order (checkout)
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:10',
            'payment_method' => 'required|string|in:bank_transfer,cod,credit_card,e_wallet',
            'notes' => 'nullable|string',
            'session_id' => 'nullable|string'
        ]);

        $sessionId = $request->session_id ?? session()->getId();
        $userId = Auth::id();

        // Get cart items
        $cartQuery = Cart::with('product');

        if ($userId) {
            $cartQuery->where('user_id', $userId);
        } else {
            $cartQuery->where('session_id', $sessionId);
        }

        $cartItems = $cartQuery->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Calculate totals
            $subtotal = $cartItems->sum('total');
            $shippingCost = 15000; // Fixed shipping cost
            $taxAmount = $subtotal * 0.1; // 10% tax
            $totalAmount = $subtotal + $shippingCost + $taxAmount;

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $userId,
                'session_id' => $sessionId,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_postal_code' => $request->shipping_postal_code,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'size' => $cartItem->size,
                    'color' => $cartItem->color,
                    'total' => $cartItem->total,
                ]);
            }

            // Clear cart after successful order
            if ($userId) {
                Cart::where('user_id', $userId)->delete();
            } else {
                Cart::where('session_id', $sessionId)->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount,
                    'payment_method' => $order->payment_method,
                    'order_id' => $order->id,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific order
     */
    public function show($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'customer_phone' => $order->customer_phone,
                'shipping_address' => $order->shipping_address,
                'shipping_city' => $order->shipping_city,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'order_status' => $order->order_status,
                'subtotal' => $order->subtotal,
                'shipping_cost' => $order->shipping_cost,
                'tax_amount' => $order->tax_amount,
                'total_amount' => $order->total_amount,
                'tracking_number' => $order->tracking_number,
                'items' => $order->orderItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product' => [
                            'id' => $item->product->id,
                            'name' => $item->product->name,
                            'image' => $item->product->image ? url('storage/' . $item->product->image) : null,
                        ],
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'size' => $item->size,
                        'color' => $item->color,
                        'total' => $item->total,
                    ];
                }),
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ]
        ]);
    }
}

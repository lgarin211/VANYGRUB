<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request): JsonResponse
    {
        $sessionId = $request->get('session_id');

        try {
            // Get orders from database and session fallback
            $orders = [];

            // Try to get from database first
            $dbOrders = Order::with(['items.product'])
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($dbOrders as $order) {
                $orders[] = [
                    'id' => $order->id,
                    'order_code' => $order->order_number,
                    'customer_phone' => $order->phone,
                    'shipping_address' => $order->shipping_address,
                    'status' => $order->status,
                    'subtotal' => $order->subtotal,
                    'discount_amount' => $order->discount_amount,
                    'total_amount' => $order->total_amount,
                    'notes' => $order->notes,
                    'items' => $order->items->map(function ($item) {
                        return [
                            'product_id' => $item->product_id,
                            'product_name' => $item->product->name ?? 'Unknown Product',
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'total' => $item->total,
                        ];
                    }),
                    'created_at' => $order->created_at->toISOString(),
                    'updated_at' => $order->updated_at->toISOString(),
                ];
            }

            // Fallback to session if database is empty and session_id provided
            if (empty($orders) && $sessionId) {
                $orders = $this->getOrdersFromSession($sessionId);
            }

            return response()->json([
                'success' => true,
                'data' => $orders,
                'message' => 'Orders retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'sometimes|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:10',
            'session_id' => 'sometimes|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
            'items.*.size' => 'sometimes|string',
            'items.*.color' => 'sometimes|string',
            'total_amount' => 'required|numeric',
            'notes' => 'sometimes|string'
        ]);

        try {
            DB::beginTransaction();

            $orderCode = $this->generateOrderCode();
            $sessionId = $request->get('session_id', Session::getId());

            // Create order in database
            // For guest orders, use a default user ID
            // In production, you should handle user authentication properly
            // Ensure default user exists or create user based on customer data
            $userId = $this->ensureUserExists($request);

            // Prepare order data
            $orderData = [
                'user_id' => $userId,
                'order_number' => $orderCode,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'status' => 'pending',
                'subtotal' => $request->total_amount,
                'discount_amount' => 0,
                'total_amount' => $request->total_amount,
                'shipping_address' => $request->shipping_address . ', ' . $request->shipping_city . ' ' . $request->shipping_postal_code,
                'phone' => $request->customer_phone,
                'notes' => $request->notes,
                'promo_code_id' => null
            ];

            \Log::info('Creating order with user_id:', $userId);

            $order = Order::create($orderData);

            // Create order items
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity']
                ]);
            }

            DB::commit();

            // Also store in session as backup
            $orderData = [
                'id' => $order->id,
                'order_code' => $orderCode,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_postal_code' => $request->shipping_postal_code,
                'items' => $request->items,
                'total_amount' => $request->total_amount,
                'notes' => $request->notes,
                'status' => 'pending',
                'created_at' => $order->created_at->toISOString(),
                'updated_at' => $order->updated_at->toISOString(),
                'session_id' => $sessionId
            ];

            $this->storeOrderInSession($sessionId, $orderData);

            // Clear cart after successful order
            $this->clearCartAfterOrder($sessionId);

            return response()->json([
                'success' => true,
                'data' => $orderData,
                'message' => 'Order created successfully'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified order.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $order = $this->findOrderById($id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified order status.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'sometimes|string|max:100',
            'notes' => 'sometimes|string'
        ]);

        try {
            $order = $this->findOrderById($id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            // Update order
            $order['status'] = $request->status;
            $order['updated_at'] = now()->toISOString();

            if ($request->has('tracking_number')) {
                $order['tracking_number'] = $request->tracking_number;
            }

            if ($request->has('notes')) {
                $order['admin_notes'] = $request->notes;
            }

            $this->updateOrderInSession($id, $order);

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified order.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $order = $this->findOrderById($id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            $this->deleteOrderFromSession($id);

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order by order code.
     */
    public function getByOrderCode(string $orderCode): JsonResponse
    {
        try {
            $order = $this->findOrderByCode($orderCode);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Order retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get orders statistics.
     */
    public function getStats(): JsonResponse
    {
        try {
            $allOrders = $this->getAllOrdersFromAllSessions();

            $stats = [
                'total_orders' => count($allOrders),
                'pending_orders' => count(array_filter($allOrders, fn($o) => $o['status'] === 'pending')),
                'processing_orders' => count(array_filter($allOrders, fn($o) => $o['status'] === 'processing')),
                'shipped_orders' => count(array_filter($allOrders, fn($o) => $o['status'] === 'shipped')),
                'delivered_orders' => count(array_filter($allOrders, fn($o) => $o['status'] === 'delivered')),
                'cancelled_orders' => count(array_filter($allOrders, fn($o) => $o['status'] === 'cancelled')),
                'total_revenue' => array_sum(array_column($allOrders, 'total_amount'))
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Order statistics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve order statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Private helper methods
     */
    private function generateOrderCode(): string
    {
        return 'VNY-' . date('Y') . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function getOrdersFromSession(?string $sessionId): array
    {
        if (!$sessionId) {
            return [];
        }

        return Session::get("orders_{$sessionId}", []);
    }

    private function getAllOrdersFromAllSessions(): array
    {
        // In a real implementation, this would query the database
        // For now, we'll simulate with session data
        $allOrders = [];

        // This is a simplified version - in production, use database
        $sessionData = Session::all();
        foreach ($sessionData as $key => $value) {
            if (strpos($key, 'orders_') === 0 && is_array($value)) {
                $allOrders = array_merge($allOrders, $value);
            }
        }

        return $allOrders;
    }

    private function storeOrderInSession(string $sessionId, array $order): void
    {
        $orders = $this->getOrdersFromSession($sessionId);
        $orders[] = $order;
        Session::put("orders_{$sessionId}", $orders);
    }

    private function findOrderById(string $id): ?array
    {
        $allOrders = $this->getAllOrdersFromAllSessions();

        foreach ($allOrders as $order) {
            if ($order['id'] === $id) {
                return $order;
            }
        }

        return null;
    }

    private function findOrderByCode(string $orderCode): ?array
    {
        $allOrders = $this->getAllOrdersFromAllSessions();

        foreach ($allOrders as $order) {
            if ($order['order_code'] === $orderCode) {
                return $order;
            }
        }

        return null;
    }

    private function updateOrderInSession(string $id, array $updatedOrder): void
    {
        $allSessions = Session::all();

        foreach ($allSessions as $key => $value) {
            if (strpos($key, 'orders_') === 0 && is_array($value)) {
                foreach ($value as $index => $order) {
                    if ($order['id'] === $id) {
                        $value[$index] = $updatedOrder;
                        Session::put($key, $value);
                        return;
                    }
                }
            }
        }
    }

    private function deleteOrderFromSession(string $id): void
    {
        $allSessions = Session::all();

        foreach ($allSessions as $key => $value) {
            if (strpos($key, 'orders_') === 0 && is_array($value)) {
                foreach ($value as $index => $order) {
                    if ($order['id'] === $id) {
                        unset($value[$index]);
                        Session::put($key, array_values($value));
                        return;
                    }
                }
            }
        }
    }

    private function clearCartAfterOrder(string $sessionId): void
    {
        // Clear the cart after successful order
        Session::forget("cart_{$sessionId}");
    }

    private function ensureUserExists($request): int
    {
        // First, check if user with ID 1 exists (default guest user)
        $defaultUser = \DB::table('vany_users')->where('id', 1)->first();
        if (!$defaultUser) {
            // Create default guest user with ID 1
            try {
                \DB::table('vany_users')->insert([
                    'id' => 1,
                    'name' => 'Guest User',
                    'email' => 'guest@vnystore.com',
                    'phone' => '000000000000',
                    'password' => \Hash::make('guestpassword'),
                    'email_verified_at' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                \Log::info('Created default guest user with ID 1');
            } catch (\Exception $e) {
                \Log::error('Failed to create default user:', ['error' => $e->getMessage()]);
            }
        }

        // Now check if customer already has an account
        $existingUser = \DB::table('vany_users')
            ->where('email', $request->customer_email)
            ->first();

        if ($existingUser) {
            \Log::info('Found existing user:', ['id' => $existingUser->id, 'email' => $existingUser->email]);
            return $existingUser->id;
        }

        // Create new user for this customer
        try {
            $userId = \DB::table('vany_users')->insertGetId([
                'name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
                'password' => \Hash::make('defaultpassword'), // Temporary password
                'email_verified_at' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            \Log::info('Created new customer user:', ['id' => $userId, 'email' => $request->customer_email]);
            return $userId;

        } catch (\Exception $e) {
            \Log::error('Failed to create customer user, using default:', ['error' => $e->getMessage()]);
            // Fallback to default user
            return 1;
        }
    }
}

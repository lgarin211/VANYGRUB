<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function invoice(Order $order)
    {
        $order->load(['items.product']);

        return view('orders.invoice', compact('order'));
    }
}

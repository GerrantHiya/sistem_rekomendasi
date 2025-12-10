<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    public function index()
    {
        $orders = Order::with(['items.variant.product', 'payment'])
            ->where('ID_Customers', Auth::guard('customer')->id())
            ->orderBy('place_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.variant.product.brand', 'items.variant.images', 'payment', 'shipment'])
            ->where('ID_Customers', Auth::guard('customer')->id())
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function cancel($id)
    {
        $order = Order::where('ID_Customers', Auth::guard('customer')->id())
            ->where('Status', Order::STATUS_PENDING)
            ->findOrFail($id);

        // Restore stock
        foreach ($order->items as $item) {
            if ($item->variant) {
                $item->variant->increment('stock_qty', 1);
            }
        }

        $order->update(['Status' => Order::STATUS_CANCELLED]);

        return back()->with('success', 'Order cancelled successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('Status', $request->status);
        }

        $orders = $query->orderBy('place_at', 'desc')->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with([
            'customer',
            'items.variant.product.brand',
            'items.variant.images',
            'payment',
            'shipment'
        ])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|in:0,1,2,3,4'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['Status' => $request->status]);

        // If shipping, create shipment record
        if ($request->status == Order::STATUS_SHIPPED && $request->tracking_number) {
            Shipment::updateOrCreate(
                ['ID_Orders' => $order->ID_Orders],
                [
                    'Tracking_Number' => $request->tracking_number,
                    'Status' => 1
                ]
            );
        }

        return back()->with('success', 'Order status updated successfully!');
    }

    public function updatePayment($id)
    {
        $order = Order::with('payment')->findOrFail($id);

        if ($order->payment) {
            $order->payment->update([
                'Status' => 1,
                'Paid_at' => now()
            ]);
        }

        return back()->with('success', 'Payment confirmed successfully!');
    }
}

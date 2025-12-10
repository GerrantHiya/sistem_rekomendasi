<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    public function index()
    {
        $cartItems = Cart::with(['variant.product.brand', 'variant.images'])
            ->where('ID_Customers', Auth::guard('customer')->id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(function($item) {
            return $item->unit_price * $item->quantity;
        });

        $deliveryCost = 25000; // Fixed delivery cost
        $total = $subtotal + $deliveryCost;

        $customer = Auth::guard('customer')->user();

        return view('checkout.index', compact('cartItems', 'subtotal', 'deliveryCost', 'total', 'customer'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postcode' => 'required|string|max:10',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:bank_transfer,cod'
        ]);

        $customerId = Auth::guard('customer')->id();
        
        $cartItems = Cart::with('variant')
            ->where('ID_Customers', $customerId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function($item) {
            return $item->unit_price * $item->quantity;
        });
        $deliveryCost = 25000;
        $total = $subtotal + $deliveryCost;

        $fullAddress = $request->shipping_address . ', ' . 
                       $request->city . ', ' . 
                       $request->province . ' ' . 
                       $request->postcode;

        DB::beginTransaction();

        try {
            // Create order
            $order = Order::create([
                'ID_Customers' => $customerId,
                'place_at' => now(),
                'Status' => Order::STATUS_PENDING,
                'Shipping_Address' => $fullAddress,
                'Discount' => 0,
                'Subtotal' => $subtotal,
                'Delivery_Cost' => $deliveryCost,
                'Total' => $total
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'ID_Orders' => $order->ID_Orders,
                    'ID_Variant' => $item->ID_Variant,
                    'Status' => 0,
                    'Shipping_Address' => $fullAddress,
                    'Discount' => 0,
                    'Subtotal' => $item->unit_price * $item->quantity,
                    'Delivery_Cost' => 0,
                    'Total' => $item->unit_price * $item->quantity
                ]);

                // Reduce stock
                $item->variant->decrement('stock_qty', $item->quantity);
            }

            // Create payment record
            Payment::create([
                'ID_Order' => $order->ID_Orders,
                'Paid_at' => $request->payment_method === 'cod' ? null : now(),
                'Status' => $request->payment_method === 'cod' ? 0 : 0
            ]);

            // Clear cart
            Cart::where('ID_Customers', $customerId)->delete();

            // Update customer address
            $customer = Auth::guard('customer')->user();
            $customer->update([
                'address' => $request->shipping_address,
                'city' => $request->city,
                'province' => $request->province,
                'postcode' => $request->postcode,
                'phone_number' => $request->phone
            ]);

            DB::commit();

            return redirect()->route('orders.show', $order->ID_Orders)
                ->with('success', 'Order placed successfully! Order ID: #' . $order->ID_Orders);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process order. Please try again.');
        }
    }
}

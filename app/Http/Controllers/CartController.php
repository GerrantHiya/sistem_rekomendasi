<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
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

        $total = $cartItems->sum(function($item) {
            return $item->unit_price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,ID_Variants',
            'quantity' => 'required|integer|min:1'
        ]);

        $variant = ProductVariant::findOrFail($request->variant_id);
        
        // Check stock
        if ($variant->stock_qty < $request->quantity) {
            return back()->with('error', 'Insufficient stock available.');
        }

        $customerId = Auth::guard('customer')->id();

        // Check if item already in cart
        $existingItem = Cart::where('ID_Customers', $customerId)
            ->where('ID_Variant', $request->variant_id)
            ->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $request->quantity;
            
            if ($variant->stock_qty < $newQuantity) {
                return back()->with('error', 'Cannot add more items. Stock limit reached.');
            }

            $existingItem->update(['quantity' => $newQuantity]);
        } else {
            Cart::create([
                'ID_Customers' => $customerId,
                'ID_Variant' => $request->variant_id,
                'unit_price' => $variant->price,
                'quantity' => $request->quantity
            ]);
        }

        return back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('ID_Cart', $id)
            ->where('ID_Customers', Auth::guard('customer')->id())
            ->firstOrFail();

        $variant = ProductVariant::find($cartItem->ID_Variant);
        
        if ($variant->stock_qty < $request->quantity) {
            return back()->with('error', 'Requested quantity exceeds available stock.');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated successfully!');
    }

    public function remove($id)
    {
        $cartItem = Cart::where('ID_Cart', $id)
            ->where('ID_Customers', Auth::guard('customer')->id())
            ->firstOrFail();

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        Cart::where('ID_Customers', Auth::guard('customer')->id())->delete();

        return back()->with('success', 'Cart cleared successfully.');
    }

    public function count()
    {
        $count = Cart::where('ID_Customers', Auth::guard('customer')->id())->sum('quantity');
        
        return response()->json(['count' => $count]);
    }
}

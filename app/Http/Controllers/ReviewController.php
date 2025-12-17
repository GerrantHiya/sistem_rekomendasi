<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    /**
     * Show the review creation form
     */
    public function create(Request $request)
    {
        $orderId = $request->order_id;
        $variantId = $request->variant_id;

        if (!$orderId || !$variantId) {
            return redirect()->route('orders.index')->with('error', 'Parameter tidak valid.');
        }

        $customerId = Auth::guard('customer')->id();

        // Get the order and verify ownership
        $order = Order::where('ID_Orders', $orderId)
            ->where('ID_Customers', $customerId)
            ->where('Status', Order::STATUS_DELIVERED)
            ->first();

        if (!$order) {
            return redirect()->route('orders.index')
                ->with('error', 'Pesanan tidak ditemukan atau belum selesai.');
        }

        // Get the variant
        $variant = ProductVariant::with(['product', 'images', 'size'])->find($variantId);

        if (!$variant || !$variant->product) {
            return redirect()->route('orders.show', $orderId)
                ->with('error', 'Produk tidak ditemukan.');
        }

        $product = $variant->product;

        // Check if already reviewed
        $existingReview = ProductReview::where('ID_Products', $product->ID_Products)
            ->where('ID_Customers', $customerId)
            ->first();

        if ($existingReview) {
            return redirect()->route('orders.show', $orderId)
                ->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        return view('reviews.create', compact('order', 'variant', 'product'));
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'order_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'review' => 'required|string|min:10|max:2000',
        ]);

        $productId = $request->product_id;
        $customerId = Auth::guard('customer')->id();

        // Check if customer already reviewed this product
        $existingReview = ProductReview::where('ID_Products', $productId)
            ->where('ID_Customers', $customerId)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan review untuk produk ini.');
        }

        // Verify that customer has a DELIVERED order containing this product
        $deliveredOrder = Order::where('ID_Customers', $customerId)
            ->where('Status', Order::STATUS_DELIVERED)
            ->where('ID_Orders', $request->order_id)
            ->whereHas('items.variant', function($q) use ($productId) {
                $q->where('ID_Product', $productId);
            })
            ->first();

        if (!$deliveredOrder) {
            return back()->with('error', 'Anda harus membeli dan menerima produk ini terlebih dahulu.');
        }

        ProductReview::create([
            'ID_Products' => $productId,
            'ID_Customers' => $customerId,
            'ID_Orders' => $request->order_id,
            'rating' => $request->rating,
            'title' => $request->title,
            'review' => $request->review,
            'is_verified_purchase' => true,
            'is_approved' => null, // Pending approval
        ]);

        return redirect()->route('orders.show', $request->order_id)
            ->with('success', 'Terima kasih! Ulasan Anda sedang menunggu persetujuan.');
    }
}

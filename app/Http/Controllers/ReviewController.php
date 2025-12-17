<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'review' => 'required|string|min:10|max:2000',
        ]);

        $product = Product::findOrFail($productId);
        $customerId = Auth::guard('customer')->id();

        // Check if customer already reviewed this product
        $existingReview = ProductReview::where('ID_Products', $productId)
            ->where('ID_Customers', $customerId)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan review untuk produk ini.');
        }

        // Check if customer has purchased this product (verified purchase)
        $hasPurchased = Order::where('ID_Customers', $customerId)
            ->whereIn('Status', [Order::STATUS_PROCESSING, Order::STATUS_SHIPPED, Order::STATUS_DELIVERED])
            ->whereHas('items.variant', function($q) use ($productId) {
                $q->where('ID_Product', $productId);
            })
            ->exists();

        ProductReview::create([
            'ID_Products' => $productId,
            'ID_Customers' => $customerId,
            'ID_Orders' => null,
            'rating' => $request->rating,
            'title' => $request->title,
            'review' => $request->review,
            'is_verified_purchase' => $hasPurchased,
            'is_approved' => null, // Pending approval
        ]);

        return back()->with('success', 'Terima kasih! Review Anda sedang menunggu persetujuan.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of all reviews
     */
    public function index(Request $request)
    {
        $query = ProductReview::with(['product', 'customer', 'order'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        $status = $request->get('status', 'all');
        if ($status === 'pending') {
            $query->whereNull('is_approved');
        } elseif ($status === 'approved') {
            $query->where('is_approved', true);
        } elseif ($status === 'rejected') {
            $query->where('is_approved', false);
        }

        $reviews = $query->paginate(15);
        
        // Get counts for tabs
        $counts = [
            'all' => ProductReview::count(),
            'pending' => ProductReview::whereNull('is_approved')->count(),
            'approved' => ProductReview::where('is_approved', true)->count(),
            'rejected' => ProductReview::where('is_approved', false)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'status', 'counts'));
    }

    /**
     * Display the specified review
     */
    public function show($id)
    {
        $review = ProductReview::with(['product', 'customer', 'order'])->findOrFail($id);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Approve a review
     */
    public function approve($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->update(['is_approved' => true]);

        return redirect()->back()->with('success', 'Review berhasil disetujui!');
    }

    /**
     * Reject a review
     */
    public function reject($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->update(['is_approved' => false]);

        return redirect()->back()->with('success', 'Review ditolak.');
    }

    /**
     * Remove the specified review
     */
    public function destroy($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review berhasil dihapus!');
    }
}

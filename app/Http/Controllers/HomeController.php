<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Services\HybridRecommendationService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected HybridRecommendationService $recommendationService;

    public function __construct(HybridRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function index()
    {
        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();

        // Get customer ID if logged in
        $customerId = auth()->guard('customer')->check() 
            ? auth()->guard('customer')->id() 
            : null;

        // Get featured products based on search history (max 3), fallback to TF-IDF
        $featuredProducts = $this->recommendationService->getSearchBasedFeaturedProducts($customerId, 3);

        // Get personalized recommendations if logged in (using Hybrid algorithm)
        $recommendations = collect();
        if ($customerId) {
            $recommendations = $this->recommendationService->getPersonalizedRecommendations($customerId, 4);
        }

        // Get trending products for homepage
        $trendingProducts = $this->recommendationService->getTrendingProducts(8);

        // Get top rated products
        $topRatedProducts = $this->recommendationService->getTopRatedProducts(4);

        // Get newest products (max 3)
        $newestProducts = $this->recommendationService->getNewestProducts(3);

        return view('home', compact(
            'featuredProducts', 
            'categories', 
            'brands', 
            'recommendations',
            'trendingProducts',
            'topRatedProducts',
            'newestProducts'
        ));
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        if (empty($query)) {
            return redirect()->route('home');
        }

        // Track search history if user is logged in
        if (auth()->guard('customer')->check()) {
            \App\Models\SearchHistory::create([
                'ID_Customers' => auth()->guard('customer')->id(),
                'search_query' => $query,
                'searched_at' => now(),
            ]);
        }

        // Use Hybrid search (TF-IDF + Rating + Popularity)
        $products = $this->recommendationService->searchProducts($query, 20);

        // If search returns no results, fall back to basic search
        if ($products->isEmpty()) {
            $products = Product::with(['brand', 'category', 'variants.images'])
                ->where('Name', 'LIKE', "%{$query}%")
                ->orWhere('Description', 'LIKE', "%{$query}%")
                ->orWhereHas('brand', function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhereHas('category', function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->get();
        }

        return view('search', compact('products', 'query'));
    }
}

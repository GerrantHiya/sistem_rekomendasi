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
        $featuredProducts = Product::with(['brand', 'category', 'variants.images'])
            ->limit(8)
            ->get();

        $categories = Category::withCount('products')->get();
        
        $brands = Brand::withCount('products')->get();

        // Get personalized recommendations if logged in (using Hybrid algorithm)
        $recommendations = collect();
        if (auth()->guard('customer')->check()) {
            $recommendations = $this->recommendationService->getPersonalizedRecommendations(
                auth()->guard('customer')->id(),
                4
            );
        }

        // Get trending products for homepage
        $trendingProducts = $this->recommendationService->getTrendingProducts(8);

        // Get top rated products
        $topRatedProducts = $this->recommendationService->getTopRatedProducts(4);

        return view('home', compact(
            'featuredProducts', 
            'categories', 
            'brands', 
            'recommendations',
            'trendingProducts',
            'topRatedProducts'
        ));
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        if (empty($query)) {
            return redirect()->route('home');
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

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Services\TfIdfService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected TfIdfService $tfIdfService;

    public function __construct(TfIdfService $tfIdfService)
    {
        $this->tfIdfService = $tfIdfService;
    }

    public function index()
    {
        $featuredProducts = Product::with(['brand', 'category', 'variants.images'])
            ->limit(8)
            ->get();

        $categories = Category::withCount('products')->get();
        
        $brands = Brand::withCount('products')->get();

        // Get personalized recommendations if logged in
        $recommendations = collect();
        if (auth()->guard('customer')->check()) {
            $recommendations = $this->tfIdfService->getPersonalizedRecommendations(
                auth()->guard('customer')->id(),
                4
            );
        }

        return view('home', compact('featuredProducts', 'categories', 'brands', 'recommendations'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        if (empty($query)) {
            return redirect()->route('home');
        }

        // Use TF-IDF for intelligent search
        $products = $this->tfIdfService->searchProducts($query, 20);

        // If TF-IDF returns no results, fall back to basic search
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

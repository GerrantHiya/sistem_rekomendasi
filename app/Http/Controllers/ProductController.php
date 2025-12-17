<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Gender;
use App\Services\HybridRecommendationService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected HybridRecommendationService $recommendationService;

    public function __construct(HybridRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category', 'subcategory', 'gender', 'variants.images', 'reviews']);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('ID_Categories', $request->category);
        }

        // Filter by brand
        if ($request->has('brand') && $request->brand) {
            $query->where('ID_Brand', $request->brand);
        }

        // Filter by gender
        if ($request->has('gender') && $request->gender) {
            $query->where('ID_Gender', $request->gender);
        }

        // Filter by price range
        if ($request->has('min_price') && $request->min_price) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->whereHas('variants', function($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        // Filter by minimum rating
        if ($request->has('min_rating') && $request->min_rating) {
            $query->whereHas('reviews', function($q) use ($request) {
                $q->where('is_approved', true);
            }, '>=', 1)
            ->withAvg(['reviews' => function($q) {
                $q->where('is_approved', true);
            }], 'rating')
            ->having('reviews_avg_rating', '>=', $request->min_rating);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderByRaw('(SELECT MIN(price) FROM product_variants WHERE product_variants.ID_Product = products.ID_Products) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('(SELECT MIN(price) FROM product_variants WHERE product_variants.ID_Product = products.ID_Products) DESC');
                break;
            case 'name_asc':
                $query->orderBy('Name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('Name', 'desc');
                break;
            case 'rating':
                $query->withAvg(['reviews' => function($q) {
                    $q->where('is_approved', true);
                }], 'rating')
                ->orderByDesc('reviews_avg_rating');
                break;
            case 'popular':
                $query->withCount(['variants as total_orders' => function($q) {
                    $q->join('order_items', 'product_variants.ID_Variants', '=', 'order_items.ID_Variant');
                }])
                ->orderByDesc('total_orders');
                break;
            default:
                $query->orderBy('ID_Products', 'desc');
        }

        $products = $query->paginate(12);

        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();
        $genders = Gender::all();

        return view('products.index', compact('products', 'categories', 'brands', 'genders'));
    }

    public function show($id)
    {
        $product = Product::with([
            'brand', 
            'category', 
            'subcategory', 
            'gender', 
            'variants.images',
            'approvedReviews.customer'
        ])->findOrFail($id);

        // Get similar products using Hybrid algorithm (TF-IDF + Rating + Popularity)
        $similarProducts = $this->recommendationService->getSimilarProducts($product, 4);

        // Get personalized recommendations if user is logged in
        $personalizedRecommendations = collect();
        if (auth()->guard('customer')->check()) {
            $personalizedRecommendations = $this->recommendationService->getPersonalizedRecommendations(
                auth()->guard('customer')->id(),
                4
            );
        }

        return view('products.show', compact('product', 'similarProducts', 'personalizedRecommendations'));
    }

    public function byCategory($id)
    {
        $category = Category::findOrFail($id);
        
        $products = Product::with(['brand', 'category', 'variants.images', 'reviews'])
            ->where('ID_Categories', $id)
            ->paginate(12);

        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();
        $genders = Gender::all();

        return view('products.index', compact('products', 'categories', 'brands', 'genders', 'category'));
    }

    public function byBrand($id)
    {
        $brand = Brand::findOrFail($id);
        
        $products = Product::with(['brand', 'category', 'variants.images', 'reviews'])
            ->where('ID_Brand', $id)
            ->paginate(12);

        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();
        $genders = Gender::all();

        return view('products.index', compact('products', 'categories', 'brands', 'genders', 'brand'));
    }
}

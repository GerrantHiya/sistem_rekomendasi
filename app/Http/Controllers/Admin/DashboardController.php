<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Category;
use App\Models\Brand;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_customers' => Customer::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('Status', Order::STATUS_PENDING)->count(),
            'total_revenue' => Order::where('Status', Order::STATUS_DELIVERED)->sum('Total'),
            'total_categories' => Category::count(),
            'total_brands' => Brand::count(),
        ];

        $recentOrders = Order::with('customer')
            ->orderBy('place_at', 'desc')
            ->limit(5)
            ->get();

        $topProducts = Product::withCount(['variants as order_count' => function($query) {
                $query->whereHas('cartItems');
            }])
            ->orderByDesc('order_count')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts'));
    }
}

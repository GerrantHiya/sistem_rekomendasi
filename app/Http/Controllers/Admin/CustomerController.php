<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $customers = Customer::withCount('orders')
            ->orderBy('ID_Customers', 'desc')
            ->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = Customer::with(['orders' => function($query) {
            $query->orderBy('place_at', 'desc');
        }])->findOrFail($id);

        return view('admin.customers.show', compact('customer'));
    }
}

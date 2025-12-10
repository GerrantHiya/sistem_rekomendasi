<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $categories = Category::withCount(['products', 'subcategories'])->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name'
        ]);

        Category::create(['name' => $request->name]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function edit($id)
    {
        $category = Category::with('subcategories')->findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id . ',ID_Categories'
        ]);

        $category->update(['name' => $request->name]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        $category = Category::withCount('products')->findOrFail($id);

        if ($category->products_count > 0) {
            return back()->with('error', 'Cannot delete category with associated products.');
        }

        // Delete subcategories
        Subcategory::where('ID_Categories', $id)->delete();
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }

    // Subcategory methods
    public function storeSubcategory(Request $request, $categoryId)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Subcategory::create([
            'name' => $request->name,
            'ID_Categories' => $categoryId
        ]);

        return back()->with('success', 'Subcategory created successfully!');
    }

    public function destroySubcategory($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->delete();

        return back()->with('success', 'Subcategory deleted successfully!');
    }
}

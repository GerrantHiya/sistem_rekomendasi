<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Gender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $products = Product::with(['brand', 'category', 'variants'])
            ->orderBy('ID_Products', 'desc')
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $brands = Brand::all();
        $genders = Gender::all();

        return view('admin.products.create', compact('categories', 'subcategories', 'brands', 'genders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,SKU',
            'brand_id' => 'required|exists:brands,ID_Brand',
            'gender_id' => 'required|exists:gender,ID_Gender',
            'category_id' => 'required|exists:categories,ID_Categories',
            'subcategory_id' => 'required|exists:subcategories,ID_SubCategories',
            'description' => 'nullable|string',
            'variants' => 'required|array|min:1',
            'variants.*.sku' => 'required|string',
            'variants.*.color' => 'nullable|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.weight' => 'nullable|numeric|min:0',
            'variants.*.images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $product = Product::create([
            'Name' => $request->name,
            'SKU' => $request->sku,
            'ID_Brand' => $request->brand_id,
            'ID_Gender' => $request->gender_id,
            'ID_Categories' => $request->category_id,
            'ID_SubCategories' => $request->subcategory_id,
            'Description' => $request->description
        ]);

        foreach ($request->variants as $index => $variantData) {
            $variant = ProductVariant::create([
                'variant_sku' => $variantData['sku'],
                'color' => $variantData['color'] ?? null,
                'price' => $variantData['price'],
                'stock_qty' => $variantData['stock'],
                'weight_gram' => $variantData['weight'] ?? null,
                'ID_Product' => $product->ID_Products,
                'ID_Size' => 0
            ]);

            // Handle images
            if ($request->hasFile("variants.{$index}.images")) {
                foreach ($request->file("variants.{$index}.images") as $image) {
                    $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('public/products', $filename);
                    
                    ProductImage::create([
                        'image' => $filename,
                        'ID_Variant' => $variant->ID_Variants
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function edit($hash)
    {
        $product = Product::findByHash($hash);
        if (!$product) {
            abort(404);
        }
        $product->load('variants.images');
        
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $brands = Brand::all();
        $genders = Gender::all();

        return view('admin.products.edit', compact('product', 'categories', 'subcategories', 'brands', 'genders'));
    }

    public function update(Request $request, $hash)
    {
        $product = Product::findByHash($hash);
        if (!$product) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,SKU,' . $product->ID_Products . ',ID_Products',
            'brand_id' => 'required|exists:brands,ID_Brand',
            'gender_id' => 'required|exists:gender,ID_Gender',
            'category_id' => 'required|exists:categories,ID_Categories',
            'subcategory_id' => 'required|exists:subcategories,ID_SubCategories',
            'description' => 'nullable|string'
        ]);

        $product->update([
            'Name' => $request->name,
            'SKU' => $request->sku,
            'ID_Brand' => $request->brand_id,
            'ID_Gender' => $request->gender_id,
            'ID_Categories' => $request->category_id,
            'ID_SubCategories' => $request->subcategory_id,
            'Description' => $request->description
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy($hash)
    {
        $product = Product::findByHash($hash);
        if (!$product) {
            abort(404);
        }
        $product->load('variants.images');

        // Delete associated images
        foreach ($product->variants as $variant) {
            foreach ($variant->images as $image) {
                Storage::delete('public/products/' . $image->image);
                $image->delete();
            }
            $variant->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    public function getSubcategories($categoryId)
    {
        $subcategories = Subcategory::where('ID_Categories', $categoryId)->get();
        return response()->json($subcategories);
    }

    /**
     * Upload images to a specific variant
     */
    public function uploadImages(Request $request, $variantHash)
    {
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp,gif|max:5120'
        ]);

        $variant = ProductVariant::findByHash($variantHash);
        if (!$variant) {
            abort(404);
        }

        $uploadedCount = 0;
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/products', $filename);
                
                ProductImage::create([
                    'image' => $filename,
                    'ID_Variant' => $variant->ID_Variants
                ]);
                $uploadedCount++;
            }
        }

        return redirect()->back()
            ->with('success', "{$uploadedCount} gambar berhasil diupload!");
    }

    /**
     * Delete a specific image
     */
    public function deleteImage($imageHash)
    {
        $image = ProductImage::findByHash($imageHash);
        if (!$image) {
            abort(404);
        }
        
        // Delete from storage
        Storage::delete('public/products/' . $image->image);
        
        // Delete from database
        $image->delete();

        return redirect()->back()
            ->with('success', 'Gambar berhasil dihapus!');
    }

    /**
     * Show the images management page for a product
     */
    public function manageImages($hash)
    {
        $product = Product::findByHash($hash);
        if (!$product) {
            abort(404);
        }
        $product->load('variants.images');
        
        return view('admin.products.images', compact('product'));
    }
}


<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of sizes
     */
    public function index()
    {
        $sizes = Size::withCount('variants')->orderBy('ID_Size')->get();
        return view('admin.sizes.index', compact('sizes'));
    }

    /**
     * Show the form for creating a new size
     */
    public function create()
    {
        return view('admin.sizes.create');
    }

    /**
     * Store a newly created size
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20|unique:size,name',
            'chest' => 'nullable|numeric|min:0|max:200',
            'body_length' => 'nullable|numeric|min:0|max:200',
            'waist' => 'nullable|numeric|min:0|max:200',
            'hip' => 'nullable|numeric|min:0|max:200',
            'thigh' => 'nullable|numeric|min:0|max:200',
        ]);

        Size::create($request->only(['name', 'chest', 'body_length', 'waist', 'hip', 'thigh']));

        return redirect()->route('admin.sizes.index')
            ->with('success', 'Size berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified size
     */
    public function edit($id)
    {
        $size = Size::findOrFail($id);
        return view('admin.sizes.edit', compact('size'));
    }

    /**
     * Update the specified size
     */
    public function update(Request $request, $id)
    {
        $size = Size::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:20|unique:size,name,' . $id . ',ID_Size',
            'chest' => 'nullable|numeric|min:0|max:200',
            'body_length' => 'nullable|numeric|min:0|max:200',
            'waist' => 'nullable|numeric|min:0|max:200',
            'hip' => 'nullable|numeric|min:0|max:200',
            'thigh' => 'nullable|numeric|min:0|max:200',
        ]);

        $size->update($request->only(['name', 'chest', 'body_length', 'waist', 'hip', 'thigh']));

        return redirect()->route('admin.sizes.index')
            ->with('success', 'Size berhasil diupdate!');
    }

    /**
     * Remove the specified size
     */
    public function destroy($id)
    {
        $size = Size::withCount('variants')->findOrFail($id);

        if ($size->variants_count > 0) {
            return back()->with('error', 'Tidak dapat menghapus size yang masih digunakan oleh variant produk.');
        }

        $size->delete();

        return redirect()->route('admin.sizes.index')
            ->with('success', 'Size berhasil dihapus!');
    }
}

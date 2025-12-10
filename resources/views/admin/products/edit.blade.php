@extends('admin.layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="card">
    <div class="card-header">
        <span>Edit Produk: {{ $product->Name }}</span>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('admin.products.images', $product->hashed_id) }}" class="btn btn-success btn-sm">
                <i class="fas fa-images"></i> Kelola Gambar
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product->hashed_id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Nama Produk *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->Name) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">SKU *</label>
                    <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->SKU) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Brand *</label>
                    <select name="brand_id" class="form-control" required>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->ID_Brand }}" {{ $product->ID_Brand == $brand->ID_Brand ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Gender *</label>
                    <select name="gender_id" class="form-control" required>
                        @foreach($genders as $gender)
                            <option value="{{ $gender->ID_Gender }}" {{ $product->ID_Gender == $gender->ID_Gender ? 'selected' : '' }}>
                                {{ $gender->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select name="category_id" class="form-control" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->ID_Categories }}" {{ $product->ID_Categories == $category->ID_Categories ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Sub Kategori *</label>
                    <select name="subcategory_id" class="form-control" required>
                        @foreach($subcategories as $sub)
                            <option value="{{ $sub->ID_SubCategories }}" {{ $product->ID_SubCategories == $sub->ID_SubCategories ? 'selected' : '' }}>
                                {{ $sub->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $product->Description) }}</textarea>
            </div>

            <hr style="margin: 2rem 0; border: none; border-top: 1px solid var(--light);">

            <h3 style="margin-bottom: 1rem;">Varian Produk Saat Ini</h3>
            
            <div style="background: var(--light); padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Warna</th>
                            <th>Harga</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->variants as $variant)
                            <tr>
                                <td>{{ $variant->variant_sku }}</td>
                                <td>{{ $variant->color ?? '-' }}</td>
                                <td>Rp {{ number_format($variant->price, 0, ',', '.') }}</td>
                                <td>{{ $variant->stock_qty }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="border-top: 1px solid var(--light); padding-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

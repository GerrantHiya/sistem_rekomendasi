@extends('admin.layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="card">
    <div class="card-header">
        <span>Form Tambah Produk</span>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Nama Produk *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">SKU *</label>
                    <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}" required>
                    @error('sku')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Brand *</label>
                    <select name="brand_id" class="form-control" required>
                        <option value="">Pilih Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->ID_Brand }}" {{ old('brand_id') == $brand->ID_Brand ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Gender *</label>
                    <select name="gender_id" class="form-control" required>
                        <option value="">Pilih Gender</option>
                        @foreach($genders as $gender)
                            <option value="{{ $gender->ID_Gender }}" {{ old('gender_id') == $gender->ID_Gender ? 'selected' : '' }}>
                                {{ $gender->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->ID_Categories }}" {{ old('category_id') == $category->ID_Categories ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Sub Kategori *</label>
                    <select name="subcategory_id" id="subcategory_id" class="form-control" required>
                        <option value="">Pilih Sub Kategori</option>
                        @foreach($subcategories as $sub)
                            <option value="{{ $sub->ID_SubCategories }}" data-category="{{ $sub->ID_Categories }}" {{ old('subcategory_id') == $sub->ID_SubCategories ? 'selected' : '' }}>
                                {{ $sub->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            <hr style="margin: 2rem 0; border: none; border-top: 1px solid var(--light);">

            <h3 style="margin-bottom: 1rem;">Varian Produk</h3>
            
            <div id="variants-container">
                <div class="variant-item" style="background: var(--light); padding: 1.5rem; border-radius: 0.75rem; margin-bottom: 1rem;">
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">SKU Varian *</label>
                            <input type="text" name="variants[0][sku]" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Warna</label>
                            <input type="text" name="variants[0][color]" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Harga *</label>
                            <input type="number" name="variants[0][price]" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stok *</label>
                            <input type="number" name="variants[0][stock]" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Berat (gram)</label>
                        <input type="number" name="variants[0][weight]" class="form-control" style="max-width: 200px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Gambar Varian</label>
                        <input type="file" name="variants[0][images][]" class="form-control" multiple accept="image/*">
                    </div>
                </div>
            </div>

            <button type="button" onclick="addVariant()" class="btn btn-outline" style="margin-bottom: 2rem;">
                <i class="fas fa-plus"></i> Tambah Varian
            </button>

            <div style="border-top: 1px solid var(--light); padding-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let variantIndex = 1;

function addVariant() {
    const container = document.getElementById('variants-container');
    const html = `
        <div class="variant-item" style="background: var(--light); padding: 1.5rem; border-radius: 0.75rem; margin-bottom: 1rem; position: relative;">
            <button type="button" onclick="this.parentElement.remove()" style="position: absolute; top: 0.5rem; right: 0.5rem; background: #ef4444; color: white; border: none; width: 24px; height: 24px; border-radius: 50%; cursor: pointer;">×</button>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">SKU Varian *</label>
                    <input type="text" name="variants[${variantIndex}][sku]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Warna</label>
                    <input type="text" name="variants[${variantIndex}][color]" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Harga *</label>
                    <input type="number" name="variants[${variantIndex}][price]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Stok *</label>
                    <input type="number" name="variants[${variantIndex}][stock]" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Berat (gram)</label>
                <input type="number" name="variants[${variantIndex}][weight]" class="form-control" style="max-width: 200px;">
            </div>
            <div class="form-group">
                <label class="form-label">Gambar Varian</label>
                <input type="file" name="variants[${variantIndex}][images][]" class="form-control" multiple accept="image/*">
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    variantIndex++;
}

document.getElementById('category_id').addEventListener('change', function() {
    const categoryId = this.value;
    const subSelect = document.getElementById('subcategory_id');
    const options = subSelect.querySelectorAll('option[data-category]');
    
    options.forEach(option => {
        if (option.dataset.category === categoryId) {
            option.style.display = '';
        } else {
            option.style.display = 'none';
        }
    });
    subSelect.value = '';
});
</script>
@endpush
@endsection

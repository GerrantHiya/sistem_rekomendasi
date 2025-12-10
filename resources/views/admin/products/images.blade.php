@extends('admin.layouts.app')

@section('title', 'Kelola Gambar: ' . $product->Name)

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.products.index') }}" style="color: var(--gray); text-decoration: none; font-size: 0.9rem;">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Produk
    </a>
</div>

<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <span>Kelola Gambar Produk: {{ $product->Name }}</span>
        <a href="{{ route('admin.products.edit', $product->hashed_id) }}" class="btn btn-outline btn-sm">
            <i class="fas fa-edit"></i> Edit Produk
        </a>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
            @php
                $allImages = collect();
                foreach($product->variants as $variant) {
                    foreach($variant->images as $image) {
                        $allImages->push($image);
                    }
                }
            @endphp
            @forelse($allImages as $image)
                <div style="aspect-ratio: 1; background: var(--light); border-radius: 0.5rem; overflow: hidden;">
                    <img src="{{ asset('storage/products/' . $image->image) }}" 
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            @empty
                <p style="color: var(--gray); grid-column: 1/-1;">Belum ada gambar</p>
            @endforelse
        </div>
    </div>
</div>

@foreach($product->variants as $variant)
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <span>
            <strong>Varian:</strong> {{ $variant->color ?? 'Standar' }} 
            <span style="color: var(--gray);">({{ $variant->variant_sku }})</span>
        </span>
        <span class="badge badge-info">{{ $variant->images->count() }} Gambar</span>
    </div>
    <div class="card-body">
        <!-- Current Images -->
        @if($variant->images->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                @foreach($variant->images as $image)
                    <div style="background: white; border: 1px solid var(--light); border-radius: 0.75rem; padding: 0.75rem;">
                        <img src="{{ asset('storage/products/' . $image->image) }}" 
                             style="width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 0.5rem; display: block; margin-bottom: 0.75rem;">
                        <a href="{{ route('admin.products.images.delete', $image->hashed_id) }}" 
                           onclick="return confirm('Hapus gambar ini?')"
                           style="display: block; width: 100%; padding: 0.5rem 1rem; background: #ef4444; color: white; border: none; border-radius: 0.5rem; cursor: pointer; font-size: 0.875rem; font-weight: 500; text-align: center; text-decoration: none;">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 2rem; background: var(--light); border-radius: 0.75rem; margin-bottom: 1.5rem;">
                <div style="font-size: 3rem; color: var(--gray-light); margin-bottom: 0.5rem;">
                    <i class="fas fa-image"></i>
                </div>
                <p style="color: var(--gray);">Belum ada gambar untuk varian ini</p>
            </div>
        @endif

        <!-- Upload Form -->
        <form action="{{ route('admin.products.images.upload', $variant->hashed_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: flex; gap: 1rem; align-items: end;">
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label class="form-label">Tambah Gambar Baru</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*" required>
                    <small style="color: var(--gray);">Format: JPG, PNG, WebP, GIF. Maksimal 5MB per file. Bisa pilih banyak file sekaligus.</small>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-upload"></i> Upload
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach

@if($product->variants->count() == 0)
<div class="card">
    <div class="card-body" style="text-align: center; padding: 3rem;">
        <div style="font-size: 4rem; color: var(--gray-light); margin-bottom: 1rem;">
            <i class="fas fa-box-open"></i>
        </div>
        <h3 style="color: var(--gray); margin-bottom: 0.5rem;">Tidak Ada Varian</h3>
        <p style="color: var(--gray-light); margin-bottom: 1.5rem;">
            Produk ini belum memiliki varian. Tambahkan varian terlebih dahulu untuk mengupload gambar.
        </p>
        <a href="{{ route('admin.products.edit', $product->hashed_id) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Varian
        </a>
    </div>
</div>
@endif

@push('styles')
<style>
    .image-card {
        position: relative;
        background: var(--light);
        border-radius: 0.75rem;
        overflow: visible;
        text-align: center;
    }
    
    .image-card img {
        display: block;
        transition: transform 0.3s;
    }
    
    .image-card:hover img {
        transform: scale(1.02);
    }
    
    .delete-form {
        margin-top: 0.75rem;
        padding: 0 0.5rem 0.5rem;
    }
    
    .delete-form .btn {
        width: 100%;
        cursor: pointer;
        position: relative;
        z-index: 10;
    }
</style>
@endpush
@endsection

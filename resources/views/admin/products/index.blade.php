@extends('admin.layouts.app')

@section('title', 'Manajemen Produk')

@section('content')
<div class="card">
    <div class="card-header">
        <span>Daftar Produk</span>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
    </div>
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produk</th>
                    <th>Brand</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->ID_Products }}</td>
                        <td>
                            <strong>{{ $product->Name }}</strong>
                            <div style="font-size: 0.8rem; color: var(--gray);">SKU: {{ $product->SKU }}</div>
                        </td>
                        <td>{{ $product->brand->name ?? '-' }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>
                            @if($product->variants->count() > 0)
                                Rp {{ number_format($product->min_price, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $product->total_stock > 10 ? 'badge-success' : ($product->total_stock > 0 ? 'badge-warning' : 'badge-danger') }}">
                                {{ $product->total_stock }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.products.images', $product->hashed_id) }}" class="btn btn-sm btn-outline" title="Kelola Gambar">
                                    <i class="fas fa-images"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product->hashed_id) }}" class="btn btn-sm btn-outline" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.products.destroy', $product->hashed_id) }}" 
                                   class="btn btn-sm btn-danger" 
                                   title="Hapus"
                                   onclick="return confirm('Hapus produk ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--gray); padding: 2rem;">
                            Belum ada produk
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">
    {{ $products->links() }}
</div>
@endsection

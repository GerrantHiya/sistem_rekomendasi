@extends('admin.layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="card">
    <div class="card-header">
        <span>Daftar Kategori</span>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Subkategori</th>
                    <th>Produk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->ID_Categories }}</td>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td><span class="badge badge-info">{{ $category->subcategories_count }}</span></td>
                        <td><span class="badge badge-primary">{{ $category->products_count }}</span></td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.categories.edit', $category->ID_Categories) }}" class="btn btn-sm btn-outline">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.categories.destroy', $category->ID_Categories) }}" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Hapus kategori ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--gray);">Belum ada kategori</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

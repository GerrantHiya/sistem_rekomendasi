@extends('admin.layouts.app')

@section('title', 'Manajemen Brand')

@section('content')
<div class="card">
    <div class="card-header">
        <span>Daftar Brand</span>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Brand
        </a>
    </div>
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Brand</th>
                    <th>Jumlah Produk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                    <tr>
                        <td>{{ $brand->ID_Brand }}</td>
                        <td><strong>{{ $brand->name }}</strong></td>
                        <td><span class="badge badge-primary">{{ $brand->products_count }}</span></td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.brands.edit', $brand->ID_Brand) }}" class="btn btn-sm btn-outline">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.brands.destroy', $brand->ID_Brand) }}" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Hapus brand ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--gray);">Belum ada brand</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

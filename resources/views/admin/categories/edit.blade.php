@extends('admin.layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <span>Edit Kategori: {{ $category->name }}</span>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.categories.update', $category->ID_Categories) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Kategori *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                @error('name')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update
            </button>
        </form>

        <hr style="margin: 2rem 0;">

        <h3 style="margin-bottom: 1rem;">Subkategori</h3>
        
        <form action="{{ route('admin.subcategories.store', $category->ID_Categories) }}" method="POST" style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
            @csrf
            <input type="text" name="name" class="form-control" placeholder="Nama subkategori baru..." required style="flex: 1;">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </form>

        <div style="background: var(--light); padding: 1rem; border-radius: 0.5rem;">
            @forelse($category->subcategories as $sub)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid white;">
                    <span>{{ $sub->name }}</span>
                    <a href="{{ route('admin.subcategories.destroy', $sub->ID_SubCategories) }}" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Hapus subkategori ini?')">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            @empty
                <p style="color: var(--gray); text-align: center; padding: 1rem;">Belum ada subkategori</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

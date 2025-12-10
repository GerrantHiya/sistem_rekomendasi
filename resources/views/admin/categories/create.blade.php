@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <span>Form Tambah Kategori</span>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Kategori *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
        </form>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Tambah Brand')

@section('content')
<div class="card" style="max-width: 500px;">
    <div class="card-header">
        <span>Form Tambah Brand</span>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.brands.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Brand *</label>
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

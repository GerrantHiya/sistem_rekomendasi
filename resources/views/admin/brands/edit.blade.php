@extends('admin.layouts.app')

@section('title', 'Edit Brand')

@section('content')
<div class="card" style="max-width: 500px;">
    <div class="card-header">
        <span>Edit Brand</span>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.brands.update', $brand->ID_Brand) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Brand *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $brand->name) }}" required>
                @error('name')<span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update
            </button>
        </form>
    </div>
</div>
@endsection

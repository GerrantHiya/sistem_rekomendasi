@extends('admin.layouts.app')

@section('title', 'Tambah Size')

@section('content')
<div class="card">
    <div class="card-header">
        <span>Tambah Size Baru</span>
        <a href="{{ route('admin.sizes.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.sizes.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name">Nama Size <span style="color: var(--danger);">*</span></label>
                <input type="text" id="name" name="name" class="form-control" 
                       value="{{ old('name') }}" placeholder="contoh: S, M, L, XL" required>
                @error('name')
                    <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label for="chest">Chest / Lingkar Dada (cm)</label>
                    <input type="number" id="chest" name="chest" class="form-control" 
                           value="{{ old('chest') }}" step="0.1" min="0" max="200" placeholder="95">
                    @error('chest')
                        <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="body_length">Body Length / Panjang (cm)</label>
                    <input type="number" id="body_length" name="body_length" class="form-control" 
                           value="{{ old('body_length') }}" step="0.1" min="0" max="200" placeholder="72">
                    @error('body_length')
                        <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="waist">Waist / Lingkar Pinggang (cm)</label>
                    <input type="number" id="waist" name="waist" class="form-control" 
                           value="{{ old('waist') }}" step="0.1" min="0" max="200" placeholder="80">
                    @error('waist')
                        <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="hip">Hip / Lingkar Pinggul (cm)</label>
                    <input type="number" id="hip" name="hip" class="form-control" 
                           value="{{ old('hip') }}" step="0.1" min="0" max="200" placeholder="98">
                    @error('hip')
                        <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="thigh">Thigh / Lingkar Paha (cm)</label>
                    <input type="number" id="thigh" name="thigh" class="form-control" 
                           value="{{ old('thigh') }}" step="0.1" min="0" max="200" placeholder="58">
                    @error('thigh')
                        <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Size
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

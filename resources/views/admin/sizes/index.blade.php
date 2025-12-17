@extends('admin.layouts.app')

@section('title', 'Manajemen Size')

@section('content')
<div class="card">
    <div class="card-header">
        <span>Daftar Size (Ukuran Pakaian)</span>
        <a href="{{ route('admin.sizes.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Size
        </a>
    </div>
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Chest (cm)</th>
                    <th>Body Length (cm)</th>
                    <th>Waist (cm)</th>
                    <th>Hip (cm)</th>
                    <th>Thigh (cm)</th>
                    <th>Variants</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sizes as $size)
                    <tr>
                        <td><strong>{{ $size->name }}</strong></td>
                        <td>{{ $size->chest ?? '-' }}</td>
                        <td>{{ $size->body_length ?? '-' }}</td>
                        <td>{{ $size->waist ?? '-' }}</td>
                        <td>{{ $size->hip ?? '-' }}</td>
                        <td>{{ $size->thigh ?? '-' }}</td>
                        <td><span class="badge badge-primary">{{ $size->variants_count }}</span></td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.sizes.edit', $size->ID_Size) }}" class="btn btn-sm btn-outline">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($size->variants_count == 0)
                                <a href="{{ route('admin.sizes.destroy', $size->ID_Size) }}" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Hapus size ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--gray);">Belum ada data size</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <span>📏 Size Chart Info</span>
    </div>
    <div class="card-body">
        <p style="color: var(--gray); margin-bottom: 1rem;">
            Ukuran dalam <strong>cm (centimeter)</strong>. Berlaku untuk kategori <strong>Top</strong> dan <strong>Bottom</strong>.
        </p>
        <ul style="color: var(--gray); font-size: 0.9rem; padding-left: 1.5rem;">
            <li><strong>Chest</strong>: Lingkar dada</li>
            <li><strong>Body Length</strong>: Panjang badan dari bahu ke hem</li>
            <li><strong>Waist</strong>: Lingkar pinggang</li>
            <li><strong>Hip</strong>: Lingkar pinggul</li>
            <li><strong>Thigh</strong>: Lingkar paha</li>
        </ul>
    </div>
</div>
@endsection

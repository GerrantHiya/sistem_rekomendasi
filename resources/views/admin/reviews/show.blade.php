@extends('admin.layouts.app')

@section('title', 'Detail Review')

@section('content')
<div class="card">
    <div class="card-header">
        <span>Detail Review</span>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <!-- Product Info -->
        <div style="background: var(--light); padding: 1.5rem; border-radius: var(--radius); margin-bottom: 1.5rem;">
            <h4 style="margin-bottom: 0.5rem;">{{ $review->product->Name ?? '-' }}</h4>
            <div style="color: var(--gray); font-size: 0.9rem;">
                SKU: {{ $review->product->SKU ?? '-' }} • 
                {{ $review->product->brand->name ?? '' }} • 
                {{ $review->product->category->name ?? '' }}
            </div>
        </div>

        <!-- Review Details -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <h5 style="color: var(--gray); margin-bottom: 1rem;">INFORMASI REVIEWER</h5>
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 0.5rem 0; color: var(--gray);">Nama</td>
                        <td style="padding: 0.5rem 0; font-weight: 500;">{{ $review->customer->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: var(--gray);">Email</td>
                        <td style="padding: 0.5rem 0;">{{ $review->customer->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: var(--gray);">Verified Purchase</td>
                        <td style="padding: 0.5rem 0;">
                            @if($review->is_verified_purchase)
                                <span style="color: #10b981;"><i class="fas fa-check-circle"></i> Ya</span>
                            @else
                                <span style="color: var(--gray);"><i class="fas fa-times-circle"></i> Tidak</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: var(--gray);">Tanggal</td>
                        <td style="padding: 0.5rem 0;">{{ $review->created_at ? $review->created_at->format('d M Y, H:i') : '-' }}</td>
                    </tr>
                </table>
            </div>

            <div>
                <h5 style="color: var(--gray); margin-bottom: 1rem;">STATUS</h5>
                <div style="margin-bottom: 1rem;">
                    @if(is_null($review->is_approved))
                        <span class="badge" style="background: #f59e0b; font-size: 1rem; padding: 0.5rem 1rem;">⏳ Menunggu Persetujuan</span>
                    @elseif($review->is_approved)
                        <span class="badge" style="background: #10b981; font-size: 1rem; padding: 0.5rem 1rem;">✅ Disetujui</span>
                    @else
                        <span class="badge" style="background: #ef4444; font-size: 1rem; padding: 0.5rem 1rem;">❌ Ditolak</span>
                    @endif
                </div>
            </div>
        </div>

        <hr style="margin: 2rem 0; border: none; border-top: 1px solid var(--light);">

        <!-- Rating -->
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="font-size: 3rem; color: #f59e0b; margin-bottom: 0.5rem;">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star" style="opacity: {{ $i <= $review->rating ? 1 : 0.2 }};"></i>
                @endfor
            </div>
            <div style="font-size: 1.5rem; font-weight: 700;">{{ $review->rating }} / 5</div>
        </div>

        <!-- Review Content -->
        <div style="background: var(--light); padding: 2rem; border-radius: var(--radius);">
            @if($review->title)
                <h3 style="margin-bottom: 1rem;">{{ $review->title }}</h3>
            @endif
            <p style="line-height: 1.8; color: var(--dark);">{{ $review->review }}</p>
        </div>

        <!-- Actions -->
        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            @if(!$review->is_approved)
            <form action="{{ route('admin.reviews.approve', $review->ID_Reviews) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-lg" style="background: #10b981; color: white;">
                    <i class="fas fa-check"></i> Setujui Review
                </button>
            </form>
            @endif
            
            @if($review->is_approved !== false)
            <form action="{{ route('admin.reviews.reject', $review->ID_Reviews) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-lg" style="background: #ef4444; color: white;">
                    <i class="fas fa-times"></i> Tolak Review
                </button>
            </form>
            @endif

            <a href="{{ route('admin.reviews.destroy', $review->ID_Reviews) }}" 
               class="btn btn-lg btn-outline"
               onclick="return confirm('Hapus review ini permanen?')">
                <i class="fas fa-trash"></i> Hapus
            </a>
        </div>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Manajemen Review')

@section('content')
<div class="card">
    <div class="card-header">
        <span>Review Produk</span>
    </div>
    
    <!-- Filter Tabs -->
    <div style="border-bottom: 1px solid var(--light); padding: 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a href="{{ route('admin.reviews.index', ['status' => 'all']) }}" 
           class="btn {{ $status === 'all' ? 'btn-primary' : 'btn-outline' }} btn-sm">
            Semua <span class="badge" style="background: rgba(0,0,0,0.2); margin-left: 0.25rem;">{{ $counts['all'] }}</span>
        </a>
        <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" 
           class="btn {{ $status === 'pending' ? 'btn-primary' : 'btn-outline' }} btn-sm">
            Menunggu <span class="badge" style="background: #f59e0b; margin-left: 0.25rem;">{{ $counts['pending'] }}</span>
        </a>
        <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" 
           class="btn {{ $status === 'approved' ? 'btn-primary' : 'btn-outline' }} btn-sm">
            Disetujui <span class="badge" style="background: #10b981; margin-left: 0.25rem;">{{ $counts['approved'] }}</span>
        </a>
        <a href="{{ route('admin.reviews.index', ['status' => 'rejected']) }}" 
           class="btn {{ $status === 'rejected' ? 'btn-primary' : 'btn-outline' }} btn-sm">
            Ditolak <span class="badge" style="background: #ef4444; margin-left: 0.25rem;">{{ $counts['rejected'] }}</span>
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Customer</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td>
                            <div style="max-width: 200px;">
                                <strong>{{ Str::limit($review->product->Name ?? '-', 30) }}</strong>
                            </div>
                        </td>
                        <td>
                            <div>{{ $review->customer->name ?? '-' }}</div>
                            @if($review->is_verified_purchase)
                                <span style="color: #10b981; font-size: 0.75rem;"><i class="fas fa-check-circle"></i> Verified</span>
                            @endif
                        </td>
                        <td>
                            <div style="color: #f59e0b;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}" style="opacity: {{ $i <= $review->rating ? 1 : 0.3 }};"></i>
                                @endfor
                            </div>
                        </td>
                        <td>
                            <div style="max-width: 250px;">
                                @if($review->title)
                                    <strong>{{ $review->title }}</strong><br>
                                @endif
                                <span style="color: var(--gray);">{{ Str::limit($review->review, 80) }}</span>
                            </div>
                        </td>
                        <td>
                            @if(is_null($review->is_approved))
                                <span class="badge" style="background: #f59e0b;">Pending</span>
                            @elseif($review->is_approved)
                                <span class="badge" style="background: #10b981;">Approved</span>
                            @else
                                <span class="badge" style="background: #ef4444;">Rejected</span>
                            @endif
                        </td>
                        <td style="font-size: 0.85rem; color: var(--gray);">
                            {{ $review->created_at ? $review->created_at->format('d M Y H:i') : '-' }}
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.25rem; flex-wrap: wrap;">
                                <a href="{{ route('admin.reviews.show', $review->ID_Reviews) }}" class="btn btn-sm btn-outline" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(!$review->is_approved)
                                <form action="{{ route('admin.reviews.approve', $review->ID_Reviews) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="background: #10b981; color: white;" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                                @if($review->is_approved !== false)
                                <form action="{{ route('admin.reviews.reject', $review->ID_Reviews) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="background: #ef4444; color: white;" title="Reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('admin.reviews.destroy', $review->ID_Reviews) }}" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Hapus review ini?')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--gray); padding: 2rem;">
                            <i class="fas fa-comments" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                            <br>Belum ada review
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($reviews->hasPages())
    <div style="padding: 1rem; border-top: 1px solid var(--light);">
        {{ $reviews->appends(['status' => $status])->links() }}
    </div>
    @endif
</div>
@endsection

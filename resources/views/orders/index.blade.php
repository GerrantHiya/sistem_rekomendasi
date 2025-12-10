@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<section class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">
            <i class="fas fa-box"></i> Riwayat Pesanan
        </h1>

        @if($orders->count() > 0)
            <div class="card">
                <div class="card-body" style="padding: 0;">
                    @foreach($orders as $order)
                        <div style="padding: 1.5rem; border-bottom: 1px solid var(--light);">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                                <div>
                                    <div style="font-weight: 600; font-size: 1.1rem; margin-bottom: 0.25rem;">
                                        Order #{{ $order->ID_Orders }}
                                    </div>
                                    <div style="color: var(--gray); font-size: 0.9rem;">
                                        <i class="fas fa-calendar"></i> {{ $order->place_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <span class="badge badge-{{ $order->status_badge_class }}">
                                        {{ $order->status_name }}
                                    </span>
                                    <div style="font-weight: 700; font-size: 1.25rem; color: var(--primary); margin-top: 0.5rem;">
                                        Rp {{ number_format($order->Total, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            
                            <div style="display: flex; gap: 1rem; overflow-x: auto; padding-bottom: 0.5rem;">
                                @foreach($order->items->take(4) as $item)
                                    <div style="width: 60px; height: 60px; flex-shrink: 0; background: var(--light); border-radius: var(--radius);">
                                        @if($item->variant && $item->variant->images->count() > 0)
                                            <img src="{{ asset('storage/products/' . $item->variant->images->first()->image) }}" 
                                                 style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius);">
                                        @endif
                                    </div>
                                @endforeach
                                @if($order->items->count() > 4)
                                    <div style="width: 60px; height: 60px; flex-shrink: 0; background: var(--light); border-radius: var(--radius); display: flex; align-items: center; justify-content: center; font-weight: 600; color: var(--gray);">
                                        +{{ $order->items->count() - 4 }}
                                    </div>
                                @endif
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                                <span style="color: var(--gray); font-size: 0.9rem;">
                                    {{ $order->items->count() }} item
                                </span>
                                <a href="{{ route('orders.show', $order->ID_Orders) }}" class="btn btn-outline btn-sm">
                                    Lihat Detail <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pagination">
                {{ $orders->links() }}
            </div>
        @else
            <div class="card" style="text-align: center; padding: 4rem 2rem;">
                <div style="font-size: 5rem; color: var(--gray-light); margin-bottom: 1.5rem;">
                    <i class="fas fa-box-open"></i>
                </div>
                <h2 style="color: var(--gray); margin-bottom: 0.5rem;">Belum Ada Pesanan</h2>
                <p style="color: var(--gray-light); margin-bottom: 1.5rem;">
                    Anda belum memiliki riwayat pesanan
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag"></i> Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</section>
@endsection

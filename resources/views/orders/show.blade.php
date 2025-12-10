@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->ID_Orders)

@section('content')
<section class="section">
    <div class="container">
        <nav style="color: var(--gray); margin-bottom: 1rem;">
            <a href="{{ route('home') }}" style="color: var(--gray); text-decoration: none;">Beranda</a>
            <i class="fas fa-chevron-right" style="margin: 0 0.5rem; font-size: 0.75rem;"></i>
            <a href="{{ route('orders.index') }}" style="color: var(--gray); text-decoration: none;">Pesanan</a>
            <i class="fas fa-chevron-right" style="margin: 0 0.5rem; font-size: 0.75rem;"></i>
            <span>Order #{{ $order->ID_Orders }}</span>
        </nav>

        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
            <div>
                <h1 class="section-title" style="margin-bottom: 0.5rem;">
                    Order #{{ $order->ID_Orders }}
                </h1>
                <p style="color: var(--gray);">
                    <i class="fas fa-calendar"></i> {{ $order->place_at->format('d F Y, H:i') }} WIB
                </p>
            </div>
            <span class="badge badge-{{ $order->status_badge_class }}" style="font-size: 1rem; padding: 0.5rem 1rem;">
                {{ $order->status_name }}
            </span>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 380px; gap: 2rem; align-items: start;">
            <div>
                <!-- Order Items -->
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header">Item Pesanan</div>
                    <div class="card-body" style="padding: 0;">
                        @foreach($order->items as $item)
                            <div style="display: flex; gap: 1.5rem; padding: 1.5rem; border-bottom: 1px solid var(--light);">
                                <div style="width: 100px; height: 100px; flex-shrink: 0;">
                                    @if($item->variant && $item->variant->images->count() > 0)
                                        <img src="{{ asset('storage/products/' . $item->variant->images->first()->image) }}" 
                                             style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius);">
                                    @else
                                        <div style="width: 100%; height: 100%; background: var(--light); border-radius: var(--radius);"></div>
                                    @endif
                                </div>
                                <div style="flex: 1;">
                                    <h3 style="font-weight: 600; margin-bottom: 0.25rem;">
                                        @if($item->variant && $item->variant->product)
                                            <a href="{{ route('products.show', $item->variant->product->ID_Products) }}" 
                                               style="color: var(--dark); text-decoration: none;">
                                                {{ $item->variant->product->Name }}
                                            </a>
                                        @else
                                            Produk tidak tersedia
                                        @endif
                                    </h3>
                                    <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 0.5rem;">
                                        {{ $item->variant->color ?? '' }} • {{ $item->variant->variant_sku ?? '' }}
                                    </p>
                                    <div style="font-weight: 700; color: var(--primary);">
                                        Rp {{ number_format($item->Subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-map-marker-alt"></i> Alamat Pengiriman
                    </div>
                    <div class="card-body">
                        <p style="line-height: 1.8;">{{ $order->Shipping_Address }}</p>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card" style="position: sticky; top: 100px;">
                <div class="card-header">Ringkasan Pembayaran</div>
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                        <span style="color: var(--gray);">Subtotal</span>
                        <span>Rp {{ number_format($order->Subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($order->Discount > 0)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                        <span style="color: var(--gray);">Diskon</span>
                        <span style="color: var(--success);">- Rp {{ number_format($order->Discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                        <span style="color: var(--gray);">Ongkos Kirim</span>
                        <span>Rp {{ number_format($order->Delivery_Cost, 0, ',', '.') }}</span>
                    </div>
                    <hr style="border: none; border-top: 1px solid var(--light); margin: 1rem 0;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem;">
                        <span style="font-weight: 600; font-size: 1.1rem;">Total</span>
                        <span style="font-weight: 700; font-size: 1.5rem; color: var(--primary);">
                            Rp {{ number_format($order->Total, 0, ',', '.') }}
                        </span>
                    </div>

                    @if($order->payment)
                        <div class="alert {{ $order->payment->Status ? 'alert-success' : 'alert-warning' }}" style="margin-bottom: 1rem;">
                            <i class="fas {{ $order->payment->Status ? 'fa-check-circle' : 'fa-clock' }}"></i>
                            {{ $order->payment->Status ? 'Pembayaran Dikonfirmasi' : 'Menunggu Pembayaran' }}
                        </div>
                    @endif

                    @if($order->shipment && $order->shipment->Tracking_Number)
                        <div class="alert alert-info" style="margin-bottom: 1rem;">
                            <i class="fas fa-truck"></i>
                            No. Resi: {{ $order->shipment->Tracking_Number }}
                        </div>
                    @endif

                    @if($order->Status == 0)
                        <form action="{{ route('orders.cancel', $order->ID_Orders) }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                            @csrf
                            <button type="submit" class="btn btn-danger" style="width: 100%;">
                                <i class="fas fa-times"></i> Batalkan Pesanan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    @media (max-width: 992px) {
        .section .container > div[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush
@endsection

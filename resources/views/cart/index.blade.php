@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<section class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">
            <i class="fas fa-shopping-cart"></i> Keranjang Belanja
        </h1>

        @if($cartItems->count() > 0)
            <div style="display: grid; grid-template-columns: 1fr 380px; gap: 2rem; align-items: start;">
                <!-- Cart Items -->
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <span>{{ $cartItems->count() }} Item</span>
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Hapus semua item dari keranjang?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="color: var(--danger);">
                                <i class="fas fa-trash"></i> Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        @foreach($cartItems as $item)
                            <div style="display: flex; gap: 1.5rem; padding: 1.5rem; border-bottom: 1px solid var(--light);">
                                <!-- Product Image -->
                                <div style="width: 120px; height: 120px; flex-shrink: 0;">
                                    @if($item->variant && $item->variant->images->count() > 0)
                                        <img src="{{ asset('storage/products/' . $item->variant->images->first()->image) }}" 
                                             alt="{{ $item->variant->product->Name ?? '' }}"
                                             style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius);">
                                    @else
                                        <div style="width: 100%; height: 100%; background: var(--light); border-radius: var(--radius); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="color: var(--gray-light); font-size: 2rem;"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div style="flex: 1;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                        <div>
                                            <h3 style="font-weight: 600; margin-bottom: 0.25rem;">
                                                <a href="{{ route('products.show', $item->variant->product->ID_Products ?? 0) }}" 
                                                   style="color: var(--dark); text-decoration: none;">
                                                    {{ $item->variant->product->Name ?? 'Produk tidak tersedia' }}
                                                </a>
                                            </h3>
                                            <p style="color: var(--gray); font-size: 0.9rem;">
                                                {{ $item->variant->color ?? '' }} • {{ $item->variant->variant_sku ?? '' }}
                                            </p>
                                        </div>
                                        <form action="{{ route('cart.remove', $item->ID_Cart) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                                        <form action="{{ route('cart.update', $item->ID_Cart) }}" method="POST" style="display: flex; align-items: center; gap: 0.5rem;">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" onclick="updateQty(this, -1)" class="btn btn-sm btn-outline" style="width: 32px; height: 32px; padding: 0;">-</button>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" 
                                                   style="width: 60px; text-align: center; border: 1px solid var(--gray-lighter); border-radius: var(--radius); padding: 0.25rem;">
                                            <button type="button" onclick="updateQty(this, 1)" class="btn btn-sm btn-outline" style="width: 32px; height: 32px; padding: 0;">+</button>
                                            <button type="submit" class="btn btn-sm btn-primary" style="margin-left: 0.5rem;">Update</button>
                                        </form>
                                        <div style="text-align: right;">
                                            <div style="font-size: 0.85rem; color: var(--gray);">
                                                Rp {{ number_format($item->unit_price, 0, ',', '.') }} x {{ $item->quantity }}
                                            </div>
                                            <div style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="card" style="position: sticky; top: 100px;">
                    <div class="card-header">Ringkasan Pesanan</div>
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                            <span style="color: var(--gray);">Subtotal ({{ $cartItems->sum('quantity') }} item)</span>
                            <span style="font-weight: 600;">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                            <span style="color: var(--gray);">Ongkos Kirim</span>
                            <span style="font-weight: 600;">Rp 25.000</span>
                        </div>
                        <hr style="border: none; border-top: 1px solid var(--light); margin: 1rem 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem;">
                            <span style="font-weight: 600; font-size: 1.1rem;">Total</span>
                            <span style="font-weight: 700; font-size: 1.25rem; color: var(--primary);">
                                Rp {{ number_format($total + 25000, 0, ',', '.') }}
                            </span>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg" style="width: 100%;">
                            <i class="fas fa-credit-card"></i> Lanjut ke Pembayaran
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="card" style="text-align: center; padding: 4rem 2rem;">
                <div style="font-size: 5rem; color: var(--gray-light); margin-bottom: 1.5rem;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h2 style="color: var(--gray); margin-bottom: 0.5rem;">Keranjang Kosong</h2>
                <p style="color: var(--gray-light); margin-bottom: 1.5rem;">
                    Belum ada produk di keranjang belanja Anda
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag"></i> Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
    function updateQty(btn, delta) {
        const input = btn.parentElement.querySelector('input[name="quantity"]');
        const newVal = parseInt(input.value) + delta;
        if (newVal >= 1) {
            input.value = newVal;
        }
    }
</script>
@endpush

@push('styles')
<style>
    @media (max-width: 992px) {
        .section .container > div[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }
        
        .card[style*="sticky"] {
            position: static !important;
        }
    }
</style>
@endpush
@endsection

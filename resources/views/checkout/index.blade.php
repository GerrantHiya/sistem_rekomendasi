@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<section class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">
            <i class="fas fa-credit-card"></i> Checkout
        </h1>

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 400px; gap: 2rem; align-items: start;">
                <!-- Checkout Form -->
                <div>
                    <!-- Shipping Address -->
                    <div class="card" style="margin-bottom: 1.5rem;">
                        <div class="card-header">
                            <i class="fas fa-map-marker-alt"></i> Alamat Pengiriman
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">Alamat Lengkap *</label>
                                <textarea name="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" 
                                          rows="3" required>{{ old('shipping_address', $customer->address) }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div class="form-group">
                                    <label class="form-label">Kota *</label>
                                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                                           value="{{ old('city', $customer->city) }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Provinsi *</label>
                                    <input type="text" name="province" class="form-control @error('province') is-invalid @enderror" 
                                           value="{{ old('province', $customer->province) }}" required>
                                    @error('province')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div class="form-group">
                                    <label class="form-label">Kode Pos *</label>
                                    <input type="text" name="postcode" class="form-control @error('postcode') is-invalid @enderror" 
                                           value="{{ old('postcode', $customer->postcode) }}" required>
                                    @error('postcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">No. Telepon *</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $customer->phone_number) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-wallet"></i> Metode Pembayaran
                        </div>
                        <div class="card-body">
                            <div style="display: grid; gap: 1rem;">
                                <label style="display: flex; align-items: center; gap: 1rem; padding: 1.5rem; border: 2px solid var(--gray-lighter); border-radius: var(--radius); cursor: pointer; transition: var(--transition);"
                                       onmouseover="this.style.borderColor='var(--primary)'"
                                       onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='var(--gray-lighter)'">
                                    <input type="radio" name="payment_method" value="bank_transfer" checked
                                           style="width: 20px; height: 20px; accent-color: var(--primary);"
                                           onchange="updatePaymentBorder()">
                                    <div style="font-size: 2rem; color: var(--primary);">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">Transfer Bank</div>
                                        <div style="font-size: 0.85rem; color: var(--gray);">BCA, Mandiri, BNI, BRI</div>
                                    </div>
                                </label>
                                <label style="display: flex; align-items: center; gap: 1rem; padding: 1.5rem; border: 2px solid var(--gray-lighter); border-radius: var(--radius); cursor: pointer; transition: var(--transition);"
                                       onmouseover="this.style.borderColor='var(--primary)'"
                                       onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='var(--gray-lighter)'">
                                    <input type="radio" name="payment_method" value="cod"
                                           style="width: 20px; height: 20px; accent-color: var(--primary);"
                                           onchange="updatePaymentBorder()">
                                    <div style="font-size: 2rem; color: var(--success);">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">Cash on Delivery (COD)</div>
                                        <div style="font-size: 0.85rem; color: var(--gray);">Bayar saat barang tiba</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="card" style="position: sticky; top: 100px;">
                    <div class="card-header">Ringkasan Pesanan</div>
                    <div class="card-body">
                        <!-- Items -->
                        <div style="max-height: 300px; overflow-y: auto; margin-bottom: 1rem;">
                            @foreach($cartItems as $item)
                                <div style="display: flex; gap: 1rem; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--light);">
                                    <div style="width: 60px; height: 60px; flex-shrink: 0;">
                                        @if($item->variant && $item->variant->images->count() > 0)
                                            <img src="{{ asset('storage/products/' . $item->variant->images->first()->image) }}" 
                                                 style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius);">
                                        @else
                                            <div style="width: 100%; height: 100%; background: var(--light); border-radius: var(--radius);"></div>
                                        @endif
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 500; font-size: 0.9rem;">{{ $item->variant->product->Name ?? '' }}</div>
                                        <div style="font-size: 0.8rem; color: var(--gray);">x{{ $item->quantity }}</div>
                                    </div>
                                    <div style="font-weight: 600;">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                            <span style="color: var(--gray);">Subtotal</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                            <span style="color: var(--gray);">Ongkos Kirim</span>
                            <span>Rp {{ number_format($deliveryCost, 0, ',', '.') }}</span>
                        </div>
                        <hr style="border: none; border-top: 1px solid var(--light); margin: 1rem 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem;">
                            <span style="font-weight: 600; font-size: 1.1rem;">Total</span>
                            <span style="font-weight: 700; font-size: 1.5rem; color: var(--primary);">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                            <i class="fas fa-lock"></i> Bayar Sekarang
                        </button>
                        
                        <p style="text-align: center; color: var(--gray); font-size: 0.8rem; margin-top: 1rem;">
                            <i class="fas fa-shield-alt"></i> Pembayaran aman dan terenkripsi
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
    function updatePaymentBorder() {
        document.querySelectorAll('input[name="payment_method"]').forEach(input => {
            const label = input.closest('label');
            label.style.borderColor = input.checked ? 'var(--primary)' : 'var(--gray-lighter)';
        });
    }
    updatePaymentBorder();
</script>
@endpush

@push('styles')
<style>
    @media (max-width: 992px) {
        .section .container form > div[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush
@endsection

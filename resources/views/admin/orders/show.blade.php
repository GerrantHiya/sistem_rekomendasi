@extends('admin.layouts.app')

@section('title', 'Detail Pesanan #' . $order->ID_Orders)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.5rem;">
    <div>
        <a href="{{ route('admin.orders.index') }}" style="color: var(--gray); text-decoration: none; font-size: 0.9rem;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>
    <span class="badge badge-{{ $order->status_badge_class }}" style="font-size: 1rem; padding: 0.5rem 1rem;">
        {{ $order->status_name }}
    </span>
</div>

<div style="display: grid; grid-template-columns: 1fr 350px; gap: 1.5rem;">
    <div>
        <!-- Order Items -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">Item Pesanan</div>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Varian</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <div style="width: 50px; height: 50px; background: var(--light); border-radius: 0.5rem; overflow: hidden;">
                                            @if($item->variant && $item->variant->images->count() > 0)
                                                <img src="{{ asset('storage/products/' . $item->variant->images->first()->image) }}" 
                                                     style="width: 100%; height: 100%; object-fit: cover;">
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $item->variant->product->Name ?? '-' }}</strong>
                                            <div style="font-size: 0.8rem; color: var(--gray);">{{ $item->variant->product->brand->name ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $item->variant->color ?? '-' }}<br>
                                    <small style="color: var(--gray);">{{ $item->variant->variant_sku ?? '' }}</small>
                                </td>
                                <td><strong>Rp {{ number_format($item->Subtotal, 0, ',', '.') }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">Informasi Pelanggan</div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <div style="color: var(--gray); font-size: 0.85rem;">Nama</div>
                        <div style="font-weight: 500;">{{ $order->customer->name ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="color: var(--gray); font-size: 0.85rem;">Email</div>
                        <div style="font-weight: 500;">{{ $order->customer->email ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="color: var(--gray); font-size: 0.85rem;">Telepon</div>
                        <div style="font-weight: 500;">{{ $order->customer->phone_number ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="color: var(--gray); font-size: 0.85rem;">Tanggal Order</div>
                        <div style="font-weight: 500;">{{ $order->place_at->format('d F Y, H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Address -->
        <div class="card">
            <div class="card-header">Alamat Pengiriman</div>
            <div class="card-body">
                <p>{{ $order->Shipping_Address }}</p>
            </div>
        </div>
    </div>

    <div>
        <!-- Order Summary -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">Ringkasan</div>
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
                    <span style="color: var(--gray);">Ongkir</span>
                    <span>Rp {{ number_format($order->Delivery_Cost, 0, ',', '.') }}</span>
                </div>
                <hr style="margin: 1rem 0; border: none; border-top: 1px solid var(--light);">
                <div style="display: flex; justify-content: space-between;">
                    <strong>Total</strong>
                    <strong style="font-size: 1.25rem; color: var(--primary);">Rp {{ number_format($order->Total, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>

        <!-- Update Status -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">Update Status</div>
            <div class="card-body">
                <form action="{{ route('admin.orders.status', $order->ID_Orders) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <select name="status" class="form-control">
                            <option value="0" {{ $order->Status == 0 ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ $order->Status == 1 ? 'selected' : '' }}>Processing</option>
                            <option value="2" {{ $order->Status == 2 ? 'selected' : '' }}>Shipped</option>
                            <option value="3" {{ $order->Status == 3 ? 'selected' : '' }}>Delivered</option>
                            <option value="4" {{ $order->Status == 4 ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="tracking_number" class="form-control" placeholder="No. Resi (untuk shipped)" 
                               value="{{ $order->shipment->Tracking_Number ?? '' }}">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="card">
            <div class="card-header">Pembayaran</div>
            <div class="card-body">
                @if($order->payment)
                    <div class="alert {{ $order->payment->Status ? 'alert-success' : 'alert-warning' }}" style="margin-bottom: 1rem;">
                        <i class="fas {{ $order->payment->Status ? 'fa-check-circle' : 'fa-clock' }}"></i>
                        {{ $order->payment->Status ? 'Sudah Dibayar' : 'Belum Dibayar' }}
                    </div>
                    @if(!$order->payment->Status)
                        <form action="{{ route('admin.orders.payment', $order->ID_Orders) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success" style="width: 100%;">
                                <i class="fas fa-check"></i> Konfirmasi Pembayaran
                            </button>
                        </form>
                    @endif
                @else
                    <p style="color: var(--gray); text-align: center;">Tidak ada data pembayaran</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

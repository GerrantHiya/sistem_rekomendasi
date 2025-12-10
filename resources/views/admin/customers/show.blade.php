@extends('admin.layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.customers.index') }}" style="color: var(--gray); text-decoration: none; font-size: 0.9rem;">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div style="display: grid; grid-template-columns: 300px 1fr; gap: 1.5rem;">
    <!-- Customer Info -->
    <div class="card">
        <div class="card-body" style="text-align: center;">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 2rem; color: white; font-weight: 700;">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </span>
            </div>
            <h2 style="font-size: 1.25rem; margin-bottom: 0.25rem;">{{ $customer->name }}</h2>
            <p style="color: var(--gray);">{{ $customer->email }}</p>

            <div style="margin-top: 1.5rem; text-align: left; border-top: 1px solid var(--light); padding-top: 1.5rem;">
                <div style="margin-bottom: 1rem;">
                    <div style="color: var(--gray); font-size: 0.85rem;">Telepon</div>
                    <div>{{ $customer->phone_number ?? '-' }}</div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <div style="color: var(--gray); font-size: 0.85rem;">Alamat</div>
                    <div>{{ $customer->address ?: '-' }}</div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <div style="color: var(--gray); font-size: 0.85rem;">Kota</div>
                    <div>{{ $customer->city ?: '-' }}, {{ $customer->province ?: '' }}</div>
                </div>
                <div>
                    <div style="color: var(--gray); font-size: 0.85rem;">Login Terakhir</div>
                    <div>{{ \Carbon\Carbon::parse($customer->last_login)->format('d F Y, H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders -->
    <div class="card">
        <div class="card-header">Riwayat Pesanan</div>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customer->orders as $order)
                        <tr>
                            <td>#{{ $order->ID_Orders }}</td>
                            <td>{{ $order->place_at->format('d/m/Y H:i') }}</td>
                            <td><strong>Rp {{ number_format($order->Total, 0, ',', '.') }}</strong></td>
                            <td><span class="badge badge-{{ $order->status_badge_class }}">{{ $order->status_name }}</span></td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->ID_Orders) }}" class="btn btn-sm btn-outline">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--gray); padding: 2rem;">
                                Belum ada pesanan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

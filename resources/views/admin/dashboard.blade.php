@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-value">{{ $stats['total_products'] }}</div>
        <div class="stat-label">Total Produk</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-value">{{ $stats['total_customers'] }}</div>
        <div class="stat-label">Total Pelanggan</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-value">{{ $stats['total_orders'] }}</div>
        <div class="stat-label">Total Pesanan</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-value">{{ $stats['pending_orders'] }}</div>
        <div class="stat-label">Pesanan Pending</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    <!-- Recent Orders -->
    <div class="card">
        <div class="card-header">
            <span><i class="fas fa-shopping-cart"></i> Pesanan Terbaru</span>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline">Lihat Semua</a>
        </div>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td>#{{ $order->ID_Orders }}</td>
                            <td>{{ $order->customer->name ?? '-' }}</td>
                            <td>Rp {{ number_format($order->Total, 0, ',', '.') }}</td>
                            <td><span class="badge badge-{{ $order->status_badge_class }}">{{ $order->status_name }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--gray);">Belum ada pesanan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Products -->
    <div class="card">
        <div class="card-header">
            <span><i class="fas fa-star"></i> Produk Populer</span>
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline">Lihat Semua</a>
        </div>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Brand</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts as $product)
                        <tr>
                            <td><strong>{{ $product->Name }}</strong></td>
                            <td>{{ $product->brand->name ?? '-' }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--gray);">Belum ada produk</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- TF-IDF Info -->
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <span><i class="fas fa-brain"></i> Sistem Rekomendasi TF-IDF</span>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
            <div>
                <h4 style="color: var(--primary); margin-bottom: 0.5rem;">
                    <i class="fas fa-search"></i> Pencarian Cerdas
                </h4>
                <p style="color: var(--gray); font-size: 0.9rem;">
                    Algoritma TF-IDF menghitung relevansi produk berdasarkan query pencarian dengan mempertimbangkan frekuensi kata dan keunikannya.
                </p>
            </div>
            <div>
                <h4 style="color: var(--success); margin-bottom: 0.5rem;">
                    <i class="fas fa-magic"></i> Rekomendasi Produk Serupa
                </h4>
                <p style="color: var(--gray); font-size: 0.9rem;">
                    Menggunakan Cosine Similarity untuk menemukan produk dengan konten serupa berdasarkan nama, deskripsi, brand, dan kategori.
                </p>
            </div>
            <div>
                <h4 style="color: var(--warning); margin-bottom: 0.5rem;">
                    <i class="fas fa-user-check"></i> Personalisasi
                </h4>
                <p style="color: var(--gray); font-size: 0.9rem;">
                    Memberikan rekomendasi personal berdasarkan riwayat belanja dan preferensi pelanggan untuk meningkatkan konversi.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

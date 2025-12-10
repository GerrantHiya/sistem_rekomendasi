@extends('admin.layouts.app')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="card">
    <div class="card-header">
        <span>Daftar Pesanan</span>
        <form action="{{ route('admin.orders.index') }}" method="GET" style="display: flex; gap: 1rem;">
            <select name="status" class="form-control" style="width: auto;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Processing</option>
                <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Shipped</option>
                <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Delivered</option>
                <option value="4" {{ request('status') === '4' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </form>
    </div>
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->ID_Orders }}</td>
                        <td>
                            <strong>{{ $order->customer->name ?? '-' }}</strong>
                            <div style="font-size: 0.8rem; color: var(--gray);">{{ $order->customer->email ?? '' }}</div>
                        </td>
                        <td>{{ $order->place_at->format('d/m/Y H:i') }}</td>
                        <td><span class="badge badge-info">{{ $order->items->count() }}</span></td>
                        <td><strong>Rp {{ number_format($order->Total, 0, ',', '.') }}</strong></td>
                        <td><span class="badge badge-{{ $order->status_badge_class }}">{{ $order->status_name }}</span></td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->ID_Orders) }}" class="btn btn-sm btn-outline">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--gray); padding: 2rem;">
                            Belum ada pesanan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">
    {{ $orders->links() }}
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Manajemen Pelanggan')

@section('content')
<div class="card">
    <div class="card-header">
        <span>Daftar Pelanggan</span>
    </div>
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Pesanan</th>
                    <th>Login Terakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>{{ $customer->ID_Customers }}</td>
                        <td><strong>{{ $customer->name }}</strong></td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone_number ?? '-' }}</td>
                        <td><span class="badge badge-primary">{{ $customer->orders_count }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($customer->last_login)->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.customers.show', $customer->ID_Customers) }}" class="btn btn-sm btn-outline">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--gray); padding: 2rem;">
                            Belum ada pelanggan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">
    {{ $customers->links() }}
</div>
@endsection

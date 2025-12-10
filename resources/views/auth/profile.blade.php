@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<section class="section">
    <div class="container">
        <h1 class="section-title" style="margin-bottom: 2rem;">
            <i class="fas fa-user-circle"></i> Profil Saya
        </h1>

        <div style="display: grid; grid-template-columns: 300px 1fr; gap: 2rem; align-items: start;">
            <!-- Sidebar -->
            <div class="card">
                <div class="card-body" style="text-align: center; padding: 2rem;">
                    <div style="width: 100px; height: 100px; background: var(--gradient-primary); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;">
                        <span style="font-size: 2.5rem; color: white; font-weight: 700;">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </span>
                    </div>
                    <h2 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 0.25rem;">{{ $customer->name }}</h2>
                    <p style="color: var(--gray); font-size: 0.9rem;">{{ $customer->email }}</p>
                    
                    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--light); text-align: left;">
                        <a href="{{ route('orders.index') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; color: var(--dark); text-decoration: none; border-radius: var(--radius); transition: var(--transition);" onmouseover="this.style.background='var(--light)'" onmouseout="this.style.background='transparent'">
                            <i class="fas fa-box" style="color: var(--primary);"></i>
                            Pesanan Saya
                        </a>
                        <a href="{{ route('cart.index') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; color: var(--dark); text-decoration: none; border-radius: var(--radius); transition: var(--transition);" onmouseover="this.style.background='var(--light)'" onmouseout="this.style.background='transparent'">
                            <i class="fas fa-shopping-cart" style="color: var(--primary);"></i>
                            Keranjang
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div>
                <!-- Profile Info -->
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header">
                        <i class="fas fa-user"></i> Informasi Profil
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div class="form-group">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $customer->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $customer->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" 
                                       value="{{ old('phone_number', $customer->phone_number) }}">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Alamat</label>
                                <textarea name="address" class="form-control" rows="2">{{ old('address', $customer->address) }}</textarea>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                                <div class="form-group">
                                    <label class="form-label">Kota</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city', $customer->city) }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Provinsi</label>
                                    <input type="text" name="province" class="form-control" value="{{ old('province', $customer->province) }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Kode Pos</label>
                                    <input type="text" name="postcode" class="form-control" value="{{ old('postcode', $customer->postcode) }}">
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-lock"></i> Ubah Password
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label class="form-label">Password Saat Ini</label>
                                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                <div class="form-group">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-key"></i> Ubah Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    @media (max-width: 992px) {
        .section .container > div[style*="grid-template-columns: 300px"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush
@endsection

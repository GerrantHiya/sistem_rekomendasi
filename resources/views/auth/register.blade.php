@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<section class="section" style="min-height: calc(100vh - 200px); display: flex; align-items: center;">
    <div class="container">
        <div style="max-width: 500px; margin: 0 auto;">
            <div class="card">
                <div class="card-body" style="padding: 2.5rem;">
                    <div style="text-align: center; margin-bottom: 2rem;">
                        <div style="font-size: 3rem; margin-bottom: 1rem; background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            <i class="fas fa-gem"></i>
                        </div>
                        <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem;">Buat Akun Baru</h1>
                        <p style="color: var(--gray);">Bergabung dengan TokoGH sekarang</p>
                    </div>

                    <form action="{{ route('register.submit') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <div style="position: relative;">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       placeholder="John Doe" value="{{ old('name') }}" required
                                       style="padding-left: 2.5rem;">
                                <i class="fas fa-user" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-light);"></i>
                            </div>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <div style="position: relative;">
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       placeholder="nama@email.com" value="{{ old('email') }}" required
                                       style="padding-left: 2.5rem;">
                                <i class="fas fa-envelope" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-light);"></i>
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">No. Telepon (Opsional)</label>
                            <div style="position: relative;">
                                <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" 
                                       placeholder="08123456789" value="{{ old('phone_number') }}"
                                       style="padding-left: 2.5rem;">
                                <i class="fas fa-phone" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-light);"></i>
                            </div>
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <div style="position: relative;">
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="Minimal 8 karakter" required
                                       style="padding-left: 2.5rem;">
                                <i class="fas fa-lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-light);"></i>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password</label>
                            <div style="position: relative;">
                                <input type="password" name="password_confirmation" class="form-control" 
                                       placeholder="Ulangi password" required
                                       style="padding-left: 2.5rem;">
                                <i class="fas fa-lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-light);"></i>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 1rem;">
                            <i class="fas fa-user-plus"></i> Daftar
                        </button>
                    </form>

                    <div style="text-align: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--light);">
                        <p style="color: var(--gray);">
                            Sudah punya akun? 
                            <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">
                                Masuk
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

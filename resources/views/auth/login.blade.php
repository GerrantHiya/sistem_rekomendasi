@extends('layouts.app')

@section('title', 'Login')

@section('content')
<section class="section" style="min-height: calc(100vh - 200px); display: flex; align-items: center;">
    <div class="container">
        <div style="max-width: 450px; margin: 0 auto;">
            <div class="card">
                <div class="card-body" style="padding: 2.5rem;">
                    <div style="text-align: center; margin-bottom: 2rem;">
                        <div style="font-size: 3rem; margin-bottom: 1rem; background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            <i class="fas fa-gem"></i>
                        </div>
                        <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem;">Selamat Datang</h1>
                        <p style="color: var(--gray);">Masuk ke akun TokoGH Anda</p>
                    </div>

                    <form action="{{ route('login.submit') }}" method="POST">
                        @csrf
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
                            <label class="form-label">Password</label>
                            <div style="position: relative;">
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="••••••••" required
                                       style="padding-left: 2.5rem;">
                                <i class="fas fa-lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-light);"></i>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 1rem;">
                            <i class="fas fa-sign-in-alt"></i> Masuk
                        </button>
                    </form>

                    <div style="text-align: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--light);">
                        <p style="color: var(--gray);">
                            Belum punya akun? 
                            <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">
                                Daftar Sekarang
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <p style="text-align: center; color: var(--gray); font-size: 0.85rem; margin-top: 1.5rem;">
                <a href="{{ route('admin.login') }}" style="color: var(--gray); text-decoration: none;">
                    <i class="fas fa-user-shield"></i> Login Admin
                </a>
            </p>
        </div>
    </div>
</section>
@endsection

@extends('layouts.app')

@section('title', 'Beri Ulasan - ' . $product->Name)

@section('content')
<section class="section">
    <div class="container" style="max-width: 700px;">
        <nav style="color: var(--gray); margin-bottom: 1rem;">
            <a href="{{ route('home') }}" style="color: var(--gray); text-decoration: none;">Beranda</a>
            <i class="fas fa-chevron-right" style="margin: 0 0.5rem; font-size: 0.75rem;"></i>
            <a href="{{ route('orders.index') }}" style="color: var(--gray); text-decoration: none;">Pesanan</a>
            <i class="fas fa-chevron-right" style="margin: 0 0.5rem; font-size: 0.75rem;"></i>
            <a href="{{ route('orders.show', $order->ID_Orders) }}" style="color: var(--gray); text-decoration: none;">Order #{{ $order->ID_Orders }}</a>
            <i class="fas fa-chevron-right" style="margin: 0 0.5rem; font-size: 0.75rem;"></i>
            <span>Beri Ulasan</span>
        </nav>

        <div class="card">
            <div class="card-header">
                <span><i class="fas fa-star" style="color: #f59e0b;"></i> Beri Ulasan Produk</span>
            </div>
            <div class="card-body">
                <!-- Product Info -->
                <div style="display: flex; gap: 1rem; padding: 1rem; background: var(--light); border-radius: var(--radius); margin-bottom: 2rem;">
                    <div style="width: 80px; height: 80px; flex-shrink: 0;">
                        @if($variant->images->count() > 0)
                            <img src="{{ $variant->images->first()->image }}" 
                                 style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius);">
                        @else
                            <div style="width: 100%; height: 100%; background: var(--gray-lighter); border-radius: var(--radius);"></div>
                        @endif
                    </div>
                    <div>
                        <h3 style="font-weight: 600; margin-bottom: 0.25rem;">{{ $product->Name }}</h3>
                        <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 0.25rem;">
                            {{ $variant->color ?? '' }} • {{ $variant->variant_sku ?? '' }}
                            @if($variant->size && $variant->size->name !== 'N/A')
                                • Size: {{ $variant->size->name }}
                            @endif
                        </p>
                        <span style="color: var(--primary); font-weight: 600;">Rp {{ number_format($variant->price, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger" style="margin-bottom: 1rem;">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->ID_Products }}">
                    <input type="hidden" name="order_id" value="{{ $order->ID_Orders }}">
                    <input type="hidden" name="variant_id" value="{{ $variant->ID_Variants }}">

                    <!-- Star Rating -->
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" style="font-weight: 600; font-size: 1rem;">Rating Produk *</label>
                        <div class="star-rating-selector" style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <label style="cursor: pointer;">
                                    <input type="radio" name="rating" value="{{ $i }}" style="display: none;" required {{ old('rating') == $i ? 'checked' : '' }}>
                                    <i class="far fa-star star-icon" data-rating="{{ $i }}" 
                                       style="font-size: 2.5rem; color: #f59e0b; transition: all 0.2s;"></i>
                                </label>
                            @endfor
                        </div>
                        <p style="color: var(--gray); font-size: 0.85rem; margin-top: 0.5rem;">Klik bintang untuk memberi rating</p>
                        @error('rating')
                            <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" style="font-weight: 600;">Judul Ulasan (Opsional)</label>
                        <input type="text" name="title" class="form-control" 
                               value="{{ old('title') }}"
                               placeholder="Contoh: Produk sangat bagus!" maxlength="255">
                    </div>

                    <!-- Review Text -->
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" style="font-weight: 600;">Ulasan *</label>
                        <textarea name="review" class="form-control" rows="5" 
                                  placeholder="Bagikan pengalaman Anda dengan produk ini. Apa yang Anda suka? Apakah sesuai ekspektasi?"
                                  required minlength="10" maxlength="2000">{{ old('review') }}</textarea>
                        <p style="color: var(--gray); font-size: 0.85rem; margin-top: 0.25rem;">Minimal 10 karakter</p>
                        @error('review')
                            <span style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                        <a href="{{ route('orders.show', $order->ID_Orders) }}" class="btn btn-outline">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Kirim Ulasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-icon');
    const radioInputs = document.querySelectorAll('input[name="rating"]');
    
    // Initialize based on checked radio
    radioInputs.forEach(radio => {
        if (radio.checked) {
            updateStars(parseInt(radio.value));
        }
    });

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            updateStars(rating);
        });

        star.addEventListener('mouseover', function() {
            const rating = parseInt(this.dataset.rating);
            previewStars(rating);
        });

        star.addEventListener('mouseout', function() {
            const checkedRadio = document.querySelector('input[name="rating"]:checked');
            if (checkedRadio) {
                updateStars(parseInt(checkedRadio.value));
            } else {
                resetStars();
            }
        });
    });

    function updateStars(rating) {
        stars.forEach((s, index) => {
            if (index < rating) {
                s.classList.remove('far');
                s.classList.add('fas');
                s.style.transform = 'scale(1.1)';
            } else {
                s.classList.remove('fas');
                s.classList.add('far');
                s.style.transform = 'scale(1)';
            }
        });
    }

    function previewStars(rating) {
        stars.forEach((s, index) => {
            if (index < rating) {
                s.classList.remove('far');
                s.classList.add('fas');
            } else {
                s.classList.remove('fas');
                s.classList.add('far');
            }
        });
    }

    function resetStars() {
        stars.forEach(s => {
            s.classList.remove('fas');
            s.classList.add('far');
            s.style.transform = 'scale(1)';
        });
    }
});
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Beranda')
@section('description', 'Fit & Go - Temukan perlengkapan olahraga dan gaya hidup aktif dengan rekomendasi AI cerdas')

@section('content')
<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content fade-in">
            <h1>Start Your Fitness Journey</h1>
            <p>Temukan perlengkapan olahraga terbaik dengan sistem rekomendasi AI. Kami membantu menemukan produk yang sempurna untuk gaya hidup aktif Anda.</p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag"></i> Mulai Belanja
                </a>
                <a href="#categories" class="btn btn-outline btn-lg" style="border-color: white; color: white;">
                    <i class="fas fa-th-large"></i> Lihat Kategori
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="section" id="categories">
    <div class="container">
        <h2 class="section-title">Kategori Populer</h2>
        <p class="section-subtitle">Temukan produk berdasarkan kategori favorit</p>
        
        <div class="categories-grid">
            @php
                $categoryIcons = [
                    'Accessories' => 'fa-gem',
                    'Top' => 'fa-tshirt',
                    'Bottom' => 'fa-socks',
                    'Shoes' => 'fa-shoe-prints'
                ];
                $categoryGradients = [
                    'Accessories' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'Top' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                    'Bottom' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                    'Shoes' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)'
                ];
            @endphp
            @foreach($categories as $category)
                <a href="{{ route('products.category', $category->ID_Categories) }}" class="category-card" style="background: {{ $categoryGradients[$category->name] ?? 'var(--gradient-primary)' }}">
                    <div class="category-icon">
                        <i class="fas {{ $categoryIcons[$category->name] ?? 'fa-box' }}"></i>
                    </div>
                    <div class="category-name">{{ $category->name }}</div>
                    <div class="category-count">{{ $category->products_count }} Produk</div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="section" style="background: linear-gradient(180deg, var(--light) 0%, white 100%);">
    <div class="container">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
            <div>
                <h2 class="section-title">Produk Unggulan</h2>
                <p class="section-subtitle" style="margin-bottom: 0;">Koleksi produk terbaik untuk Anda</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="products-grid">
            @foreach($featuredProducts as $product)
                <div class="product-card fade-in">
                    <div class="product-image">
                        @if($product->first_image)
                            <img src="{{ asset('storage/products/' . $product->first_image) }}" alt="{{ $product->Name }}">
                        @else
                            <img src="https://via.placeholder.com/400x400?text={{ urlencode($product->Name) }}" alt="{{ $product->Name }}">
                        @endif
                        @if($product->total_stock < 5 && $product->total_stock > 0)
                            <span class="product-badge">Stok Terbatas</span>
                        @endif
                        <div class="product-actions">
                            <a href="{{ route('products.show', $product->ID_Products) }}" class="product-action-btn" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @auth('customer')
                            <form action="{{ route('cart.add') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="variant_id" value="{{ $product->variants->first()->ID_Variants ?? '' }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="product-action-btn" title="Tambah ke Keranjang">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </form>
                            @endauth
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-brand">{{ $product->brand->name ?? '-' }}</div>
                        <a href="{{ route('products.show', $product->ID_Products) }}" class="product-name">{{ $product->Name }}</a>
                        <div class="product-category">
                            {{ $product->category->name ?? '' }} • {{ $product->gender->name ?? '' }}
                        </div>
                        <div class="product-price">
                            Rp {{ number_format($product->min_price, 0, ',', '.') }}
                            @if($product->min_price != $product->max_price)
                                <small style="font-weight: 400; color: var(--gray);">- Rp {{ number_format($product->max_price, 0, ',', '.') }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Recommendations Section (for logged in users) -->
@if($recommendations->count() > 0)
<section class="section">
    <div class="container">
        <div class="recommendation-section">
            <div class="recommendation-header">
                <div class="recommendation-icon">
                    <i class="fas fa-magic"></i>
                </div>
                <div>
                    <h2 class="section-title" style="margin-bottom: 0;">Rekomendasi Untuk Anda</h2>
                    <p class="section-subtitle" style="margin-bottom: 0; margin-top: 0.25rem;">Berdasarkan riwayat belanja Anda</p>
                </div>
                <span class="tfidf-badge">
                    <i class="fas fa-brain"></i> Powered by Hybrid AI
                </span>
            </div>
            
            <div class="products-grid">
                @foreach($recommendations as $product)
                    <div class="product-card fade-in">
                        <div class="product-image">
                            @if($product->first_image)
                                <img src="{{ asset('storage/products/' . $product->first_image) }}" alt="{{ $product->Name }}">
                            @else
                                <img src="https://via.placeholder.com/400x400?text={{ urlencode($product->Name) }}" alt="{{ $product->Name }}">
                            @endif
                            @if(isset($product->recommendation_score))
                                <span class="similarity-score">
                                    <i class="fas fa-chart-line"></i> {{ $product->recommendation_score }}% Match
                                </span>
                            @endif
                        </div>
                        <div class="product-info">
                            <div class="product-brand">{{ $product->brand->name ?? '-' }}</div>
                            <a href="{{ route('products.show', $product->ID_Products) }}" class="product-name">{{ $product->Name }}</a>
                            <div class="product-category">
                                {{ $product->category->name ?? '' }} • {{ $product->gender->name ?? '' }}
                            </div>
                            <div class="product-price">
                                Rp {{ number_format($product->min_price, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- Brands Section -->
<section class="section">
    <div class="container">
        <h2 class="section-title" style="text-align: center;">Brand Ternama</h2>
        <p class="section-subtitle" style="text-align: center;">Koleksi dari brand premium pilihan</p>
        
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 2rem; margin-top: 2rem;">
            @foreach($brands as $brand)
                <a href="{{ route('products.brand', $brand->ID_Brand) }}" 
                   style="background: white; padding: 1.5rem 2.5rem; border-radius: var(--radius-lg); box-shadow: var(--shadow); text-decoration: none; color: var(--dark); font-weight: 600; transition: var(--transition);"
                   onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='var(--shadow-lg)';"
                   onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow)';">
                    {{ $brand->name }}
                    <span style="color: var(--gray); font-weight: 400; margin-left: 0.5rem;">({{ $brand->products_count }})</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section" style="background: var(--gradient-dark); color: white;">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 3rem; text-align: center;">
            <div class="fade-in">
                <div style="font-size: 3rem; margin-bottom: 1rem;">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Pengiriman Cepat</h3>
                <p style="color: var(--gray-light);">Gratis ongkir untuk pembelian di atas Rp 500.000</p>
            </div>
            <div class="fade-in">
                <div style="font-size: 3rem; margin-bottom: 1rem;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Produk Original</h3>
                <p style="color: var(--gray-light);">100% produk asli dengan garansi resmi</p>
            </div>
            <div class="fade-in">
                <div style="font-size: 3rem; margin-bottom: 1rem;">
                    <i class="fas fa-brain"></i>
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Smart AI Recommendation</h3>
                <p style="color: var(--gray-light);">Sistem cerdas untuk menemukan produk yang tepat</p>
            </div>
            <div class="fade-in">
                <div style="font-size: 3rem; margin-bottom: 1rem;">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Layanan 24/7</h3>
                <p style="color: var(--gray-light);">Tim support siap membantu kapan saja</p>
            </div>
        </div>
    </div>
</section>
@endsection

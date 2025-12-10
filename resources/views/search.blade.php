@extends('layouts.app')

@section('title', 'Hasil Pencarian: ' . $query)
@section('description', 'Hasil pencarian untuk "' . $query . '" menggunakan algoritma TF-IDF')

@section('content')
<section class="section">
    <div class="container">
        <div style="margin-bottom: 2rem;">
            <nav style="color: var(--gray); margin-bottom: 1rem;">
                <a href="{{ route('home') }}" style="color: var(--gray); text-decoration: none;">Beranda</a>
                <i class="fas fa-chevron-right" style="margin: 0 0.5rem; font-size: 0.75rem;"></i>
                <span>Pencarian</span>
            </nav>
            
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h1 class="section-title" style="margin-bottom: 0.5rem;">
                        Hasil Pencarian: "{{ $query }}"
                    </h1>
                    <p style="color: var(--gray);">
                        Ditemukan {{ $products->count() }} produk
                    </p>
                </div>
                <span class="tfidf-badge">
                    <i class="fas fa-brain"></i> TF-IDF Search
                </span>
            </div>
        </div>

        @if($products->count() > 0)
            <div class="products-grid">
                @foreach($products as $product)
                    <div class="product-card fade-in">
                        <div class="product-image">
                            @if($product->first_image)
                                <img src="{{ asset('storage/products/' . $product->first_image) }}" alt="{{ $product->Name }}">
                            @else
                                <img src="https://via.placeholder.com/400x400?text={{ urlencode($product->Name) }}" alt="{{ $product->Name }}">
                            @endif
                            @if(isset($product->relevance_score))
                                <span class="similarity-score">
                                    <i class="fas fa-chart-line"></i> {{ $product->relevance_score }}% Relevan
                                </span>
                            @endif
                            <div class="product-actions">
                                <a href="{{ route('products.show', $product->ID_Products) }}" class="product-action-btn" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
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
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card" style="text-align: center; padding: 4rem 2rem;">
                <div style="font-size: 4rem; color: var(--gray-light); margin-bottom: 1rem;">
                    <i class="fas fa-search"></i>
                </div>
                <h2 style="color: var(--gray); margin-bottom: 0.5rem;">Tidak Ada Hasil</h2>
                <p style="color: var(--gray-light); margin-bottom: 1.5rem;">
                    Tidak ditemukan produk untuk pencarian "{{ $query }}"
                </p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Lihat Semua Produk
                </a>
            </div>
        @endif
    </div>
</section>
@endsection

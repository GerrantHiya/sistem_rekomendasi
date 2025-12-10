@extends('layouts.app')

@section('title', $product->Name)
@section('description', Str::limit($product->Description, 160))

@section('content')
<section class="section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav style="color: var(--gray); margin-bottom: 2rem;">
            <a href="{{ route('home') }}" style="color: var(--gray); text-decoration: none;">Beranda</a>
            <i class="fas fa-chevron-right" style="margin: 0 0.5rem; font-size: 0.75rem;"></i>
            <a href="{{ route('products.index') }}" style="color: var(--gray); text-decoration: none;">Produk</a>
            <i class="fas fa-chevron-right" style="margin: 0 0.5rem; font-size: 0.75rem;"></i>
            <a href="{{ route('products.category', $product->category->ID_Categories ?? 0) }}" style="color: var(--gray); text-decoration: none;">{{ $product->category->name ?? '' }}</a>
            <i class="fas fa-chevron-right" style="margin: 0 0.5rem; font-size: 0.75rem;"></i>
            <span style="color: var(--dark);">{{ $product->Name }}</span>
        </nav>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: start;">
            <!-- Product Images -->
            <div>
                <div class="card" style="padding: 2rem; margin-bottom: 1rem;">
                    @php
                        $images = collect();
                        foreach($product->variants as $variant) {
                            $images = $images->merge($variant->images);
                        }
                    @endphp
                    
                    @if($images->count() > 0)
                        <img id="main-image" src="{{ asset('storage/products/' . $images->first()->image) }}" 
                             alt="{{ $product->Name }}" 
                             style="width: 100%; border-radius: var(--radius); cursor: zoom-in;"
                             onclick="this.style.transform = this.style.transform === 'scale(1.5)' ? 'scale(1)' : 'scale(1.5)';">
                    @else
                        <img src="https://via.placeholder.com/600x600?text={{ urlencode($product->Name) }}" 
                             alt="{{ $product->Name }}" 
                             style="width: 100%; border-radius: var(--radius);">
                    @endif
                </div>

                @if($images->count() > 1)
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.5rem;">
                        @foreach($images as $image)
                            <div class="card" style="padding: 0.5rem; cursor: pointer;" onclick="document.getElementById('main-image').src='{{ asset('storage/products/' . $image->image) }}'">
                                <img src="{{ asset('storage/products/' . $image->image) }}" 
                                     alt="{{ $product->Name }}" 
                                     style="width: 100%; border-radius: var(--radius); aspect-ratio: 1; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div>
                <div style="margin-bottom: 1rem;">
                    <a href="{{ route('products.brand', $product->brand->ID_Brand ?? 0) }}" 
                       style="color: var(--primary); font-weight: 600; text-transform: uppercase; text-decoration: none; letter-spacing: 0.5px;">
                        {{ $product->brand->name ?? '' }}
                    </a>
                </div>
                
                <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $product->Name }}</h1>
                
                <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; color: var(--gray);">
                    <span><i class="fas fa-tag"></i> {{ $product->category->name ?? '' }}</span>
                    <span><i class="fas fa-layer-group"></i> {{ $product->subcategory->name ?? '' }}</span>
                    <span><i class="fas fa-venus-mars"></i> {{ $product->gender->name ?? '' }}</span>
                </div>

                <div style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 1.5rem;">
                    Rp {{ number_format($product->min_price, 0, ',', '.') }}
                    @if($product->min_price != $product->max_price)
                        <span style="font-size: 1.25rem; color: var(--gray); font-weight: 400;">
                            - Rp {{ number_format($product->max_price, 0, ',', '.') }}
                        </span>
                    @endif
                </div>

                <div style="margin-bottom: 2rem;">
                    <p style="color: var(--gray); line-height: 1.8;">{{ $product->Description }}</p>
                </div>

                <!-- Variants -->
                @auth('customer')
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Pilih Varian</label>
                        <div style="display: grid; gap: 0.75rem;">
                            @foreach($product->variants as $index => $variant)
                                <label style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 2px solid var(--gray-lighter); border-radius: var(--radius); cursor: pointer; transition: var(--transition);"
                                       onmouseover="this.style.borderColor='var(--primary)'"
                                       onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='var(--gray-lighter)'">
                                    <input type="radio" name="variant_id" value="{{ $variant->ID_Variants }}" 
                                           {{ $index === 0 ? 'checked' : '' }}
                                           style="width: 20px; height: 20px; accent-color: var(--primary);"
                                           onchange="this.closest('form').querySelectorAll('label').forEach(l => l.style.borderColor='var(--gray-lighter)'); this.closest('label').style.borderColor='var(--primary)';">
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600;">{{ $variant->color ?? 'Standard' }}</div>
                                        <div style="font-size: 0.85rem; color: var(--gray);">
                                            SKU: {{ $variant->variant_sku }} • 
                                            Stok: {{ $variant->stock_qty }}
                                        </div>
                                    </div>
                                    <div style="font-weight: 700; color: var(--primary);">
                                        Rp {{ number_format($variant->price, 0, ',', '.') }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jumlah</label>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <button type="button" onclick="decrementQty()" class="btn btn-outline" style="width: 50px; height: 50px; padding: 0;">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" 
                                   class="form-control" style="width: 100px; text-align: center; font-weight: 600;">
                            <button type="button" onclick="incrementQty()" class="btn btn-outline" style="width: 50px; height: 50px; padding: 0;">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary btn-lg" style="flex: 1;">
                            <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                        </button>
                    </div>
                </form>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <a href="{{ route('login') }}" style="color: inherit; text-decoration: underline;">Login</a> untuk menambahkan produk ke keranjang
                    </div>
                @endauth

                <!-- Product Details -->
                <div class="card" style="margin-top: 2rem;">
                    <div class="card-header">Detail Produk</div>
                    <div class="card-body">
                        <table style="width: 100%;">
                            <tr style="border-bottom: 1px solid var(--light);">
                                <td style="padding: 0.75rem 0; color: var(--gray);">Brand</td>
                                <td style="padding: 0.75rem 0; font-weight: 500;">{{ $product->brand->name ?? '-' }}</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--light);">
                                <td style="padding: 0.75rem 0; color: var(--gray);">Kategori</td>
                                <td style="padding: 0.75rem 0; font-weight: 500;">{{ $product->category->name ?? '-' }}</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--light);">
                                <td style="padding: 0.75rem 0; color: var(--gray);">Sub Kategori</td>
                                <td style="padding: 0.75rem 0; font-weight: 500;">{{ $product->subcategory->name ?? '-' }}</td>
                            </tr>
                            <tr style="border-bottom: 1px solid var(--light);">
                                <td style="padding: 0.75rem 0; color: var(--gray);">Gender</td>
                                <td style="padding: 0.75rem 0; font-weight: 500;">{{ $product->gender->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.75rem 0; color: var(--gray);">SKU</td>
                                <td style="padding: 0.75rem 0; font-weight: 500;">{{ $product->SKU }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Similar Products (TF-IDF Recommendations) -->
@if($similarProducts->count() > 0)
<section class="section" style="background: var(--light);">
    <div class="container">
        <div class="recommendation-section" style="background: white;">
            <div class="recommendation-header">
                <div class="recommendation-icon">
                    <i class="fas fa-magic"></i>
                </div>
                <div>
                    <h2 class="section-title" style="margin-bottom: 0;">Produk Serupa</h2>
                    <p class="section-subtitle" style="margin-bottom: 0; margin-top: 0.25rem;">Berdasarkan kemiripan konten (TF-IDF Cosine Similarity)</p>
                </div>
                <span class="tfidf-badge">
                    <i class="fas fa-brain"></i> TF-IDF Algorithm
                </span>
            </div>
            
            <div class="products-grid">
                @foreach($similarProducts as $similar)
                    <div class="product-card fade-in">
                        <div class="product-image">
                            @if($similar->first_image)
                                <img src="{{ asset('storage/products/' . $similar->first_image) }}" alt="{{ $similar->Name }}">
                            @else
                                <img src="https://via.placeholder.com/400x400?text={{ urlencode($similar->Name) }}" alt="{{ $similar->Name }}">
                            @endif
                            <span class="similarity-score">
                                <i class="fas fa-chart-line"></i> {{ $similar->similarity_score }}% Mirip
                            </span>
                            <div class="product-actions">
                                <a href="{{ route('products.show', $similar->ID_Products) }}" class="product-action-btn" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-brand">{{ $similar->brand->name ?? '-' }}</div>
                            <a href="{{ route('products.show', $similar->ID_Products) }}" class="product-name">{{ $similar->Name }}</a>
                            <div class="product-category">
                                {{ $similar->category->name ?? '' }} • {{ $similar->gender->name ?? '' }}
                            </div>
                            <div class="product-price">
                                Rp {{ number_format($similar->min_price, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

@push('styles')
<style>
    @media (max-width: 992px) {
        .section .container > div[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function incrementQty() {
        const input = document.getElementById('quantity');
        input.value = parseInt(input.value) + 1;
    }
    
    function decrementQty() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Semua Produk')
@section('description', 'Jelajahi koleksi lengkap produk premium di TokoGH')

@section('content')
<section class="section">
    <div class="container">
        <div style="margin-bottom: 2rem;">
            <nav style="color: var(--gray); margin-bottom: 1rem;">
                <a href="{{ route('home') }}" style="color: var(--gray); text-decoration: none;">Beranda</a>
                <i class="fas fa-chevron-right" style="margin: 0 0.5rem; font-size: 0.75rem;"></i>
                @if(isset($category))
                    <span>{{ $category->name }}</span>
                @elseif(isset($brand))
                    <span>{{ $brand->name }}</span>
                @else
                    <span>Semua Produk</span>
                @endif
            </nav>
            <h1 class="section-title">
                @if(isset($category))
                    {{ $category->name }}
                @elseif(isset($brand))
                    {{ $brand->name }}
                @else
                    Semua Produk
                @endif
            </h1>
        </div>

        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 2rem;">
            <!-- Sidebar Filters -->
            <aside>
                <div class="card" style="position: sticky; top: 100px;">
                    <div class="card-header">
                        <i class="fas fa-filter"></i> Filter
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.index') }}" method="GET">
                            <!-- Categories -->
                            <div class="form-group">
                                <label class="form-label">Kategori</label>
                                <select name="category" class="form-control">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->ID_Categories }}" {{ request('category') == $cat->ID_Categories ? 'selected' : '' }}>
                                            {{ $cat->name }} ({{ $cat->products_count }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Brands -->
                            <div class="form-group">
                                <label class="form-label">Brand</label>
                                <select name="brand" class="form-control">
                                    <option value="">Semua Brand</option>
                                    @foreach($brands as $br)
                                        <option value="{{ $br->ID_Brand }}" {{ request('brand') == $br->ID_Brand ? 'selected' : '' }}>
                                            {{ $br->name }} ({{ $br->products_count }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Gender -->
                            <div class="form-group">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="">Semua</option>
                                    @foreach($genders as $gen)
                                        <option value="{{ $gen->ID_Gender }}" {{ request('gender') == $gen->ID_Gender ? 'selected' : '' }}>
                                            {{ $gen->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Range -->
                            <div class="form-group">
                                <label class="form-label">Harga Minimum</label>
                                <input type="number" name="min_price" class="form-control" placeholder="Rp 0" value="{{ request('min_price') }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Harga Maksimum</label>
                                <input type="number" name="max_price" class="form-control" placeholder="Rp 100.000.000" value="{{ request('max_price') }}">
                            </div>

                            <!-- Sort -->
                            <div class="form-group">
                                <label class="form-label">Urutkan</label>
                                <select name="sort" class="form-control">
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                <i class="fas fa-search"></i> Terapkan Filter
                            </button>
                            
                            @if(request()->hasAny(['category', 'brand', 'gender', 'min_price', 'max_price']))
                                <a href="{{ route('products.index') }}" class="btn btn-outline" style="width: 100%; margin-top: 0.5rem;">
                                    <i class="fas fa-times"></i> Reset Filter
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Products Grid -->
            <div>
                @if($products->count() > 0)
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                        <p style="color: var(--gray);">
                            Menampilkan {{ $products->firstItem() }}-{{ $products->lastItem() }} dari {{ $products->total() }} produk
                        </p>
                    </div>

                    <div class="products-grid">
                        @foreach($products as $product)
                            <div class="product-card fade-in">
                                <div class="product-image">
                                    @if($product->first_image)
                                        <img src="{{ asset('storage/products/' . $product->first_image) }}" alt="{{ $product->Name }}">
                                    @else
                                        <img src="https://via.placeholder.com/400x400?text={{ urlencode($product->Name) }}" alt="{{ $product->Name }}">
                                    @endif
                                    @if($product->total_stock < 5 && $product->total_stock > 0)
                                        <span class="product-badge">Stok Terbatas</span>
                                    @elseif($product->total_stock == 0)
                                        <span class="product-badge" style="background: var(--danger);">Habis</span>
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
                                        @if($product->min_price != $product->max_price)
                                            <small style="font-weight: 400; color: var(--gray);">- Rp {{ number_format($product->max_price, 0, ',', '.') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="pagination">
                        @if($products->previousPageUrl())
                            <a href="{{ $products->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
                        @endif
                        
                        @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            <a href="{{ $url }}" class="{{ $page == $products->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                        @endforeach
                        
                        @if($products->nextPageUrl())
                            <a href="{{ $products->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
                        @endif
                    </div>
                @else
                    <div class="card" style="text-align: center; padding: 4rem 2rem;">
                        <div style="font-size: 4rem; color: var(--gray-light); margin-bottom: 1rem;">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h2 style="color: var(--gray); margin-bottom: 0.5rem;">Tidak Ada Produk</h2>
                        <p style="color: var(--gray-light);">
                            Tidak ada produk yang sesuai dengan filter yang dipilih
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    @media (max-width: 992px) {
        .container > div[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }
        
        aside .card {
            position: static !important;
        }
    }
</style>
@endpush
@endsection

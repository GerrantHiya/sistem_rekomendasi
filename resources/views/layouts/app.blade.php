<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Fit & Go') - Your Fitness & Lifestyle Store</title>
    <meta name="description" content="@yield('description', 'Fit & Go - Temukan perlengkapan olahraga dan gaya hidup aktif berkualitas tinggi')">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #0ea5e9;
            --accent: #f59e0b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #06b6d4;
            --dark: #0f172a;
            --dark-light: #1e293b;
            --gray: #64748b;
            --gray-light: #94a3b8;
            --gray-lighter: #cbd5e1;
            --light: #f1f5f9;
            --white: #ffffff;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-dark: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-md: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 25px 50px -12px rgb(0 0 0 / 0.25);
            --radius: 0.75rem;
            --radius-lg: 1rem;
            --radius-xl: 1.5rem;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light);
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(99, 102, 241, 0.1);
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 80px;
        }

        .logo {
            font-size: 1.75rem;
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo i {
            -webkit-text-fill-color: var(--primary);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            padding: 0.5rem 0;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-primary);
            transition: var(--transition);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .search-box {
            display: flex;
            align-items: center;
            background: var(--light);
            border-radius: 50px;
            padding: 0.5rem 1rem;
            border: 2px solid transparent;
            transition: var(--transition);
            width: 300px;
        }

        .search-box:focus-within {
            border-color: var(--primary);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .search-box input {
            border: none;
            background: transparent;
            padding: 0.5rem;
            width: 100%;
            font-size: 0.9rem;
            outline: none;
        }

        .search-box button {
            background: var(--gradient-primary);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            cursor: pointer;
            transition: var(--transition);
        }

        .search-box button:hover {
            transform: scale(1.05);
        }

        .nav-icons {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-icon {
            position: relative;
            color: var(--dark);
            font-size: 1.25rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .nav-icon:hover {
            color: var(--primary);
            transform: scale(1.1);
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--gradient-secondary);
            color: white;
            font-size: 0.7rem;
            font-weight: 600;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: var(--dark);
            color: white;
        }

        .btn-secondary:hover {
            background: var(--dark-light);
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }

        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1rem;
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Hero Section */
        .hero {
            background: var(--gradient-dark);
            color: white;
            padding: 6rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            background: linear-gradient(to right, #fff, #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.25rem;
            color: var(--gray-light);
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        /* Section */
        .section {
            padding: 5rem 0;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .section-subtitle {
            color: var(--gray);
            margin-bottom: 2rem;
        }

        /* Product Card */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        .product-card {
            background: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .product-image {
            position: relative;
            padding-top: 100%;
            overflow: hidden;
            background: var(--light);
        }

        .product-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .product-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: var(--gradient-secondary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .product-actions {
            position: absolute;
            top: 1rem;
            right: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            opacity: 0;
            transform: translateX(10px);
            transition: var(--transition);
        }

        .product-card:hover .product-actions {
            opacity: 1;
            transform: translateX(0);
        }

        .product-action-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--shadow);
            transition: var(--transition);
            color: var(--dark);
        }

        .product-action-btn:hover {
            background: var(--primary);
            color: white;
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-brand {
            color: var(--primary);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .product-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
            text-decoration: none;
            display: block;
            transition: var(--transition);
        }

        .product-name:hover {
            color: var(--primary);
        }

        .product-category {
            color: var(--gray);
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .product-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
        }

        .product-price-old {
            font-size: 0.9rem;
            color: var(--gray);
            text-decoration: line-through;
            margin-left: 0.5rem;
        }

        /* Categories */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .category-card {
            background: var(--gradient-primary);
            border-radius: var(--radius-lg);
            padding: 2rem;
            text-align: center;
            color: white;
            text-decoration: none;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.1);
            opacity: 0;
            transition: var(--transition);
        }

        .category-card:hover::before {
            opacity: 1;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .category-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .category-name {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .category-count {
            font-size: 0.85rem;
            opacity: 0.8;
            margin-top: 0.25rem;
        }

        /* Footer */
        .footer {
            background: var(--gradient-dark);
            color: white;
            padding: 4rem 0 2rem;
            margin-top: 4rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-brand {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .footer-description {
            color: var(--gray-light);
            font-size: 0.9rem;
            line-height: 1.8;
        }

        .footer-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: var(--gray-light);
            text-decoration: none;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: var(--transition);
        }

        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 2rem;
            text-align: center;
            color: var(--gray-light);
            font-size: 0.85rem;
        }

        /* Forms */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--gray-lighter);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: var(--transition);
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid var(--success);
            color: var(--success);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--danger);
            color: var(--danger);
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid var(--warning);
            color: var(--warning);
        }

        .alert-info {
            background: rgba(6, 182, 212, 0.1);
            border: 1px solid var(--info);
            color: var(--info);
        }

        /* Cards */
        .card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--light);
            font-weight: 600;
            background: var(--light);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--light);
            background: var(--light);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-primary { background: rgba(99, 102, 241, 0.1); color: var(--primary); }
        .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .badge-warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .badge-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
        .badge-info { background: rgba(6, 182, 212, 0.1); color: var(--info); }

        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--light);
        }

        .table th {
            font-weight: 600;
            color: var(--gray);
            background: var(--light);
        }

        .table tbody tr:hover {
            background: rgba(99, 102, 241, 0.02);
        }

        /* Pagination */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            text-decoration: none;
            color: var(--dark);
            transition: var(--transition);
        }

        .pagination a:hover {
            background: var(--light);
        }

        .pagination .active {
            background: var(--gradient-primary);
            color: white;
        }

        /* Recommendation Section */
        .recommendation-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 3rem;
            border-radius: var(--radius-xl);
            margin: 3rem 0;
        }

        .recommendation-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .recommendation-icon {
            width: 50px;
            height: 50px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .tfidf-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: auto;
        }

        .similarity-score {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            background: rgba(99, 102, 241, 0.9);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--dark);
        }

        @media (max-width: 992px) {
            .nav-links {
                display: none;
            }

            .search-box {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .navbar-container {
                padding: 0 1rem;
            }

            .container {
                padding: 0 1rem;
            }
        }

        /* Dropdown */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            min-width: 200px;
            padding: 0.5rem 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: var(--transition);
            z-index: 100;
        }

        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: block;
            padding: 0.75rem 1.5rem;
            color: var(--dark);
            text-decoration: none;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background: var(--light);
            color: var(--primary);
        }

        .dropdown-divider {
            border-top: 1px solid var(--light);
            margin: 0.5rem 0;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }

        .fade-in {
            animation: fadeIn 0.6s ease forwards;
        }

        .pulse {
            animation: pulse 2s ease-in-out infinite;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('home') }}" class="logo">
                <i class="fas fa-dumbbell"></i>
                Fit & Go
            </a>

            <ul class="nav-links">
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li><a href="{{ route('products.index') }}">Produk</a></li>
                @foreach(\App\Models\Category::take(4)->get() as $category)
                    <li><a href="{{ route('products.category', $category->ID_Categories) }}">{{ $category->name }}</a></li>
                @endforeach
            </ul>

            <form action="{{ route('search') }}" method="GET" class="search-box">
                <input type="text" name="q" placeholder="Cari produk..." value="{{ request('q') }}">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>

            <div class="nav-icons">
                @auth('customer')
                    <a href="{{ route('cart.index') }}" class="nav-icon">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="cart-badge" id="cart-count">{{ auth('customer')->user()->cart->sum('quantity') }}</span>
                    </a>
                    <div class="dropdown">
                        <a href="#" class="nav-icon">
                            <i class="fas fa-user"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a href="{{ route('profile') }}" class="dropdown-item">
                                <i class="fas fa-user-circle"></i> Profil Saya
                            </a>
                            <a href="{{ route('orders.index') }}" class="dropdown-item">
                                <i class="fas fa-box"></i> Pesanan Saya
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item" style="width: 100%; border: none; background: none; text-align: left; cursor: pointer;">
                                    <i class="fas fa-sign-out-alt"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-icon" title="Login">
                        <i class="fas fa-user"></i>
                    </a>
                @endauth
            </div>

            <button class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Alerts -->
    @if(session('success'))
        <div class="container" style="padding-top: 1rem;">
            <div class="alert alert-success fade-in">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container" style="padding-top: 1rem;">
            <div class="alert alert-danger fade-in">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand">
                        <i class="fas fa-dumbbell"></i> Fit & Go
                    </div>
                    <p class="footer-description">
                        Perlengkapan olahraga dan gaya hidup aktif berkualitas tinggi. Belanja nyaman dan aman hanya di Fit & Go.
                    </p>
                    <div class="social-links" style="margin-top: 1.5rem;">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="footer-title">Kategori</h4>
                    <ul class="footer-links">
                        @foreach(\App\Models\Category::all() as $category)
                            <li><a href="{{ route('products.category', $category->ID_Categories) }}">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4 class="footer-title">Akun Saya</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('profile') }}">Profil</a></li>
                        <li><a href="{{ route('orders.index') }}">Pesanan</a></li>
                        <li><a href="{{ route('cart.index') }}">Keranjang</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer-title">Informasi</h4>
                    <ul class="footer-links">
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Fit & Go. All rights reserved. Powered by Hybrid AI Recommendation System.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

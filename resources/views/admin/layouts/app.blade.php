<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Fit & Go Admin</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #0ea5e9;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #06b6d4;
            --dark: #0f172a;
            --dark-light: #1e293b;
            --gray: #64748b;
            --gray-light: #94a3b8;
            --light: #f1f5f9;
            --white: #ffffff;
            --sidebar-width: 280px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light);
            color: var(--dark);
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--dark) 0%, var(--dark-light) 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-logo i {
            color: var(--primary);
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            padding: 0.75rem 1.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.5rem;
            color: var(--gray-light);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(99, 102, 241, 0.1);
            color: white;
            border-left-color: var(--primary);
        }

        .nav-link.active {
            background: rgba(99, 102, 241, 0.2);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
        }

        .topbar {
            background: white;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .content {
            padding: 2rem;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--light);
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-body { padding: 1.5rem; }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: var(--gray);
            font-size: 0.875rem;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-success { background: var(--success); color: white; }
        .btn-danger { background: var(--danger); color: white; }
        .btn-outline { background: transparent; border: 1px solid var(--gray-light); color: var(--dark); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .btn-sm { padding: 0.375rem 0.75rem; font-size: 0.8rem; }

        /* Tables */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--light);
        }

        .table th {
            font-weight: 600;
            color: var(--gray);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody tr:hover {
            background: var(--light);
        }

        /* Forms */
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-weight: 500; margin-bottom: 0.5rem; }
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-light);
            border-radius: 0.5rem;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            padding: 0.25rem 0.625rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-primary { background: rgba(99, 102, 241, 0.1); color: var(--primary); }
        .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .badge-warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .badge-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
        .badge-info { background: rgba(6, 182, 212, 0.1); color: var(--info); }

        /* Alerts */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .alert-success { background: rgba(16, 185, 129, 0.1); color: var(--success); border: 1px solid var(--success); }
        .alert-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid var(--danger); }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 0.25rem;
            margin-top: 1rem;
        }
        .pagination a, .pagination span {
            padding: 0.5rem 0.875rem;
            border-radius: 0.375rem;
            text-decoration: none;
            color: var(--dark);
            font-size: 0.875rem;
        }
        .pagination a:hover { background: var(--light); }
        .pagination .active { background: var(--primary); color: white; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
                    <i class="fas fa-dumbbell"></i>
                    Fit & Go Admin
                </a>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section">Menu Utama</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                
                <div class="nav-section">Katalog</div>
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i> Produk
                </a>
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i> Kategori
                </a>
                <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                    <i class="fas fa-tag"></i> Brand
                </a>
                
                <div class="nav-section">Transaksi</div>
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i> Pesanan
                </a>
                <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Pelanggan
                </a>
                
                <div class="nav-section">Lainnya</div>
                <a href="{{ route('home') }}" class="nav-link" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Lihat Toko
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link" style="width: 100%; background: none; border: none; text-align: left; cursor: pointer;">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="topbar">
                <h1 class="topbar-title">@yield('title', 'Dashboard')</h1>
                <div class="topbar-user">
                    <span style="color: var(--gray);">{{ auth('admin')->user()->name }}</span>
                    <div style="width: 35px; height: 35px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                        {{ strtoupper(substr(auth('admin')->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <div class="content">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>

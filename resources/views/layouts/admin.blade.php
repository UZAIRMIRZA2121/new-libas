<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - New Libas')</title>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #ffffff;
            --main-bg: #f4f7f6; /* slightly blueish gray like reference */
            --card-bg: #ffffff;
            --text-main: #334155;
            --text-muted: #64748b;
            --primary: #4f46e5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--main-bg);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
        }

        .sidebar-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-header i {
            font-size: 1.5rem;
            color: #3b82f6;
        }

        .sidebar-header h2 {
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .sidebar-menu {
            flex: 1;
            padding: 1.5rem 1rem;
            overflow-y: auto;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none;  /* IE and Edge */
        }
        .sidebar-menu::-webkit-scrollbar {
            display: none; /* Chrome, Safari and Opera */
        }

        .menu-section {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
            margin-top: 1.5rem;
            padding-left: 0.5rem;
            color: #64748b;
            font-weight: 600;
        }

        .menu-section:first-child {
            margin-top: 0;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 0.25rem;
            transition: all 0.2s;
            font-weight: 500;
        }

        .menu-link:hover, .menu-link.active {
            background-color: var(--sidebar-hover);
            color: var(--sidebar-text-active);
        }

        .menu-link i {
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #0b1120;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: #3b82f6;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .user-details h4 {
            color: white;
            font-size: 0.875rem;
        }

        .user-details span {
            font-size: 0.75rem;
            color: var(--sidebar-text);
        }

        .logout-btn {
            color: var(--sidebar-text);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            padding: 0.5rem;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .logout-btn:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Topbar */
        .admin-topbar {
            height: 70px;
            background: white;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .topbar-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .date-widget {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
            font-size: 0.875rem;
            background: var(--main-bg);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        /* Content Area */
        .admin-content {
            padding: 2rem;
            flex: 1;
        }

        /* Dashboard specific styles */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 1.5rem;
        }

        .plan-banner {
            grid-column: span 12;
            background: white;
            border-radius: 12px;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            border-left: 6px solid var(--primary);
        }

        .plan-info h3 {
            font-size: 1.1rem;
            color: var(--text-main);
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .plan-info h3 i {
            color: var(--primary);
        }

        .plan-info p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .plan-time {
            text-align: right;
        }

        .plan-time span {
            display: block;
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .plan-time strong {
            color: #10b981;
            font-size: 1.25rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            border: 1px solid #f1f5f9;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        
        .icon-blue { background: #eff6ff; color: #3b82f6; }
        .icon-purple { background: #f5f3ff; color: #8b5cf6; }
        .icon-yellow { background: #fefce8; color: #eab308; }
        .icon-red { background: #fef2f2; color: #ef4444; }
        .icon-green { background: #f0fdf4; color: #22c55e; }
        .icon-pink { background: #fdf2f8; color: #ec4899; }

        .stat-info h4 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .stat-info p {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .table-card {
            grid-column: span 8;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h3 {
            font-size: 1rem;
            font-weight: 600;
        }

        .view-all-btn {
            font-size: 0.8rem;
            color: var(--text-main);
            background: white;
            border: 1px solid #e2e8f0;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .view-all-btn:hover {
            background: #f8fafc;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            text-align: left;
            padding: 1rem 1.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 600;
            border-bottom: 1px solid #f1f5f9;
        }

        .data-table td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-main);
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-blue { background: #eff6ff; color: #3b82f6; }
        .badge-green { background: #f0fdf4; color: #22c55e; }
        .badge-yellow { background: #fefce8; color: #eab308; }

        .alerts-col {
            grid-column: span 4;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .alert-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            border: 1px solid #f1f5f9;
            padding: 1.5rem;
        }

        .alert-card h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .alert-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .alert-item:last-child {
            margin-bottom: 0;
        }

        .alert-info h4 {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-main);
        }

        .alert-info p {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <i class="fas fa-shopping-bag"></i>
            <h2>New Libas</h2>
        </div>
        
        <div class="sidebar-menu">
            <div class="menu-section">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="menu-link active">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            
            <div class="menu-section">Sales</div>
            <a href="{{ route('admin.orders.index') }}" class="menu-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>
        

            <div class="menu-section">Inventory</div>
            <a href="{{ route('admin.categories.index') }}" class="menu-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Categories
            </a>
            <a href="{{ route('admin.brands.index') }}" class="menu-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                <i class="fas fa-copyright"></i> Brands
            </a>
            <a href="{{ route('admin.products.index') }}" class="menu-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i> Products
            </a>
            <a href="{{ route('admin.banners.index') }}" class="menu-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <i class="fas fa-images"></i> Banners
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="menu-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i> Coupons
            </a>
            <a href="#" class="menu-link">
                <i class="fas fa-exclamation-triangle"></i> Alerts <span style="background: #ef4444; color: white; border-radius: 50%; width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.65rem; margin-left: auto;">1</span>
            </a>

            <div class="menu-section">Contacts</div>
            <a href="{{ route('admin.customers.index') }}" class="menu-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Customers
            </a>

            <div class="menu-section">Settings</div>
            <a href="{{ route('admin.tracking.index') }}" class="menu-link {{ request()->routeIs('admin.tracking.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Tracking Logs
            </a>
            <a href="#" class="menu-link">
                <i class="fas fa-cog"></i> Store Settings
            </a>
        </div>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="user-details">
                    <h4>{{ Auth::user()->name ?? 'Admin User' }}</h4>
                    <span>Administrator</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i></button>
            </form>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <main class="admin-main">
        <!-- Topbar -->
        <header class="admin-topbar">
            <div class="topbar-title">@yield('page_title', 'Dashboard')</div>
            <div class="topbar-right">
                <div class="date-widget">
                    <i class="far fa-sun"></i>
                    <span>{{ now()->format('D, d M Y, H:i') }}</span>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="admin-content">
            @yield('content')
        </div>
    </main>

</body>
</html>

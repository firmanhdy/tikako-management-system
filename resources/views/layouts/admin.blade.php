<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Panel')</title>

    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        /* Global Styling */
        body { font-family: 'Inter', sans-serif; background-color: #f4f6f9; }
        
        /* SIDEBAR */
        .sidebar {
            width: 260px;
            height: 100vh;
            background-color: #212529;
            color: #fff;
            position: fixed; 
            top: 0; left: 0;
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar-brand {
            height: 70px; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 1.2rem; letter-spacing: 1px;
            background-color: rgba(0,0,0,0.2); color: #ffc107; text-decoration: none;
        }
        .sidebar-link {
            display: flex; align-items: center; padding: 12px 20px;
            color: rgba(255,255,255,0.7); text-decoration: none; transition: 0.3s;
            border-left: 4px solid transparent;
        }
        .sidebar-link:hover { color: #fff; background-color: rgba(255,255,255,0.05); }
        .sidebar-link.active { color: #fff; background-color: rgba(255,255,255,0.1); border-left-color: #ffc107; }
        .sidebar-link i { font-size: 1.2rem; margin-right: 15px; width: 25px; text-align: center; }
        .sidebar-heading {
            font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;
            color: rgba(255,255,255,0.4); padding: 20px 20px 10px; font-weight: bold;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 260px; 
            display: flex; flex-direction: column;
            min-height: 100vh;
        }
        .topbar {
            height: 70px; background-color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 30px;
            position: sticky; top: 0; z-index: 999;
        }
        .content-wrapper {
            padding: 30px;
            flex: 1; 
        }
        footer {
            background-color: #fff; padding: 15px 0;
            text-align: center; color: #6c757d; font-size: 0.85rem;
            border-top: 1px solid #e9ecef;
        }
    </style>
</head>
<body>

    {{-- 1. SIDEBAR (Fixed Navigation) --}}
    <nav class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <i class="bi bi-shield-lock-fill me-2"></i> ADMIN PANEL
        </a>
        <div class="py-3">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="sidebar-heading">Operations</div>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Kitchen Orders
            </a>
            <a href="{{ route('menu.index') }}" class="sidebar-link {{ request()->routeIs('menu.*') ? 'active' : '' }}">
                <i class="bi bi-cup-hot"></i> Menu Management
            </a>

            <div class="sidebar-heading">Reports & Data</div>
            <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> Sales Report
            </a>
            <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Customer Data
            </a>
            <a href="{{ route('admin.feedback.index') }}" class="sidebar-link {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}">
                <i class="bi bi-chat-quote"></i> Feedback
            </a>
            <a href="{{ route('admin.qrcode.index') }}" class="sidebar-link {{ request()->routeIs('admin.qrcode.*') ? 'active' : '' }}">
                <i class="bi bi-qr-code"></i> Table QR Code
            </a>
            <a href="{{ route('admin.password') }}" class="sidebar-link {{ request()->routeIs('admin.password') ? 'active' : '' }}">
                <i class="bi bi-key"></i> Change Password
            </a>
        </div>
    </nav>

    {{-- 2. MAIN CONTENT --}}
    <div class="main-content">
        
        {{-- Topbar --}}
        <header class="topbar">
            <div class="fw-bold text-secondary d-none d-md-block">
                Restaurant Management System
            </div>

            {{-- User Dropdown --}}
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="dropdownUser" data-bs-toggle="dropdown">
                    <div class="text-end me-3 d-none d-md-block">
                        <div class="fw-bold small">{{ Auth::user()->name }}</div>
                        <div class="text-muted" style="font-size: 0.7rem;">Administrator</div>
                    </div>
                    <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 40px; height: 40px;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li><div class="dropdown-header">Hello, {{ Auth::user()->name }}</div></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        {{-- Content Area --}}
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

        {{-- Footer --}}
        <footer>
            &copy; {{ date('Y') }} Tikako Management System. All rights reserved.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
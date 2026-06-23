@extends('layouts.frontend')

@section('content')
<style>
    .customer-layout {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
        display: flex;
        gap: 2rem;
        min-height: 600px;
    }
    .customer-sidebar {
        width: 250px;
        background: #fff;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 1.5rem 0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        align-self: flex-start;
    }
    .customer-sidebar a {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 1.5rem;
        color: #334155;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
        border-left: 3px solid transparent;
    }
    .customer-sidebar a:hover, .customer-sidebar a.active {
        color: var(--primary-color);
        background: #f8fafc;
        border-left-color: var(--primary-color);
    }
    .customer-sidebar i {
        width: 20px;
        text-align: center;
        font-size: 1.1rem;
    }
    .customer-main {
        flex: 1;
    }
    .customer-page-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .customer-layout {
            flex-direction: column;
        }
        .customer-sidebar {
            width: 100%;
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
            padding: 0.5rem;
        }
        .customer-sidebar a {
            padding: 0.5rem 1rem;
            border-left: none;
            border-bottom: 3px solid transparent;
        }
        .customer-sidebar a:hover, .customer-sidebar a.active {
            border-left-color: transparent;
            border-bottom-color: var(--primary-color);
        }
    }
</style>

<div style="background: #f8fafc; padding: 2rem 0; min-height: 100vh;">
    <div class="customer-layout">
        <aside class="customer-sidebar">
            <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <i class="fas fa-user"></i> Profile Info
            </a>
            <a href="{{ route('profile.address') }}" class="{{ request()->routeIs('profile.address') ? 'active' : '' }}">
                <i class="fas fa-map-marker-alt"></i> Shipping Address
            </a>
            <a href="{{ route('customer.orders') }}" class="{{ request()->routeIs('customer.orders*') ? 'active' : '' }}"><i class="fas fa-clipboard-list"></i> My Order</a>
            <a href="{{ route('customer.wishlist') }}" class="{{ request()->routeIs('customer.wishlist') ? 'active' : '' }}"><i class="far fa-heart"></i> Wish List</a>
        </aside>
        
        <main class="customer-main">
            <h2 class="customer-page-title">@yield('customer_title', 'Dashboard')</h2>
            @yield('customer_content')
        </main>
    </div>
</div>
@endsection

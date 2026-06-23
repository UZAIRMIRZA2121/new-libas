@extends('layouts.admin')

@section('page_title', 'Dashboard')

@section('content')
<div class="dashboard-grid">
    
    <!-- Plan Banner -->
    <div class="plan-banner">
        <div class="plan-info">
            <h3><i class="far fa-star"></i> Current Plan: Pro E-Commerce</h3>
            <p>Your store subscription is currently active.</p>
        </div>
        <div class="plan-time">
            <span>Time Left</span>
            <strong>29 Days</strong>
        </div>
    </div>

    <!-- Stats Row -->
    <x-admin.stat-card colorClass="icon-blue" icon="fas fa-tshirt" value="145" title="Total Products" />
    <x-admin.stat-card colorClass="icon-purple" icon="fas fa-folder-open" value="12" title="Categories" />
    <x-admin.stat-card colorClass="icon-yellow" icon="fas fa-arrow-down" value="3" title="Low Stock" />
    <x-admin.stat-card colorClass="icon-red" icon="fas fa-exclamation-circle" value="8" title="Pending Orders" />
    <x-admin.stat-card colorClass="icon-green" icon="fas fa-chart-line" value="Rs. 15,400" title="Today Sales" />
    <x-admin.stat-card colorClass="icon-pink" icon="fas fa-file-invoice" value="342" title="Total Orders" />

    <!-- Main Content Row -->
    @include('admin.partials.recent-orders')

    <div class="alerts-col">
        <!-- Low Stock Alert -->
        <div class="alert-card">
            <h3>Low Stock Alert</h3>
            <x-admin.alert-item 
                title="Classic White Kurta" 
                description="Stock: 2 | Min: 5" 
                badgeText="Low" 
                badgeColor="badge-yellow" 
            />
            <x-admin.alert-item 
                title="Black Formal Trousers" 
                description="Stock: 1 | Min: 5" 
                badgeText="Low" 
                badgeColor="badge-yellow" 
            />
        </div>

        <!-- Pending Actions -->
        <div class="alert-card">
            <h3>Pending Actions</h3>
            <x-admin.alert-item 
                title="Unfulfilled Orders" 
                description="8 orders waiting for dispatch" 
                badgeText="Action Req" 
                badgeColor="badge-yellow" 
            />
        </div>
    </div>

</div>
@endsection

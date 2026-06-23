@extends('layouts.customer')
@section('customer_title', 'My Order')

@section('customer_content')
<style>
    .order-list-header {
        display: flex;
        background: #f1f5f9;
        padding: 1rem 1.5rem;
        border-radius: 8px 8px 0 0;
        font-weight: 600;
        color: #475569;
        font-size: 0.9rem;
    }
    .order-card {
        background: #fff;
        border: 1px solid #f1f5f9;
        border-top: none;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        transition: all 0.2s;
    }
    .order-card:last-child {
        border-radius: 0 0 8px 8px;
    }
    .order-card:hover {
        background: #f8fafc;
    }
    .order-icon {
        width: 60px;
        height: 60px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #ff5722;
        margin-right: 1.5rem;
    }
    .order-details-col { flex: 2; }
    .order-status-col { flex: 1; text-align: center; }
    .order-total-col { flex: 1; text-align: center; font-weight: 700; }
    .order-action-col { flex: 1; text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end; }
    
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 99px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    .status-green { background: #dcfce7; color: #16a34a; }
    .status-yellow { background: #fefce8; color: #eab308; }
    .status-blue { background: #eff6ff; color: #3b82f6; }
    .status-red { background: #fef2f2; color: #ef4444; }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    .btn-view { color: #ff5722; background: #fff5f2; }
    .btn-view:hover { border-color: #ff5722; }
    .btn-download { color: #10b981; background: #ecfdf5; }
    .btn-download:hover { border-color: #10b981; }

    @media (max-width: 768px) {
        .order-list-header {
            display: none;
        }
        .order-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            border: 1px solid #f1f5f9;
            border-radius: 8px !important;
            margin-bottom: 1rem;
        }
        .order-icon {
            margin-right: 0;
        }
        .order-status-col, .order-total-col, .order-action-col {
            text-align: left;
            display: block;
        }
        .order-total-col {
            padding: 0.5rem 0;
        }
        .order-action-col {
            justify-content: flex-start;
        }
    }
</style>

@if(session('success'))
    <div style="background: #dcfce7; color: #16a34a; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
@endif

<div class="order-list-header">
    <div style="flex: 2;">Order List</div>
    <div style="flex: 1; text-align: center;">Status</div>
    <div style="flex: 1; text-align: center;">Total</div>
    <div style="flex: 1; text-align: right;">Action</div>
</div>

@forelse($orders as $order)
<div class="order-card">
    <div class="order-icon">
        <i class="fas fa-hand-paper"></i>
    </div>
    <div class="order-details-col">
        <h4 style="margin: 0 0 0.25rem 0; font-size: 1rem; color: #1e293b;">Order #{{ $order->order_number }}</h4>
        <div style="color: #64748b; font-size: 0.85rem; margin-bottom: 0.25rem;">{{ $order->items->count() }} Items</div>
        <div style="color: #94a3b8; font-size: 0.75rem;">{{ $order->created_at->format('d M, Y h:i A') }}</div>
    </div>
    <div class="order-status-col">
        @php
            $badgeClass = 'status-yellow';
            if($order->status == 'processing') $badgeClass = 'status-blue';
            if($order->status == 'delivered' || $order->status == 'completed') $badgeClass = 'status-green';
            if($order->status == 'refund_request' || $order->status == 'refunded') $badgeClass = 'status-red';
        @endphp
        <span class="status-badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
    </div>
    <div class="order-total-col">
        Rs.{{ number_format($order->total, 2) }}
    </div>
    <div class="order-action-col">
        <a href="{{ route('customer.orders.show', $order) }}" class="action-btn btn-view" title="View Details"><i class="far fa-eye"></i></a>
        <!-- Download Invoice can link to the print route but the customer must be able to view it -->
    </div>
</div>
@empty
<div class="order-card" style="justify-content: center; color: #64748b; padding: 3rem;">
    You have no orders yet.
</div>
@endforelse

<div style="margin-top: 1rem;">
    {{ $orders->links() }}
</div>
@endsection

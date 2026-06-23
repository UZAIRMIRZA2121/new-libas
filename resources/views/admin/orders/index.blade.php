@extends('layouts.admin')

@section('page_title', 'Manage Orders')

@section('content')
<div class="dashboard-grid">
    <div class="table-card" style="grid-column: span 12;">
        <div class="card-header">
            <h3>Recent Orders</h3>
        </div>
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>
                            <div style="font-weight: 500;">{{ $order->first_name }} {{ $order->last_name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $order->email }}</div>
                        </td>
                        <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                        <td style="font-weight: 600;">Rs.{{ number_format($order->total, 2) }}</td>
                        <td>
                            @php
                                $badgeClass = 'badge-yellow';
                                if($order->status == 'processing') $badgeClass = 'badge-blue';
                                if($order->status == 'delivered' || $order->status == 'completed') $badgeClass = 'badge-green';
                                if($order->status == 'refund_request' || $order->status == 'refunded') $badgeClass = 'badge-red'; // Needs .badge-red in CSS
                            @endphp
                            <span class="badge {{ $badgeClass }}" style="{{ $badgeClass == 'badge-red' ? 'background: #fef2f2; color: #ef4444;' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="view-all-btn" style="background: var(--primary); color: white; padding: 0.35rem 0.75rem; text-decoration: none;">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">No orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 1rem;">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection

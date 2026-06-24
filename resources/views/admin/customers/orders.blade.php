@extends('layouts.admin')

@section('page_title', 'Customer Orders')

@section('content')
<div class="dashboard-grid">
    <div class="table-card" style="grid-column: span 12;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="margin-bottom: 0.25rem;">Orders for: {{ $customer->name }}</h3>
                <p style="font-size: 0.85rem; color: var(--text-muted);">{{ $customer->email }}</p>
            </div>
            <a href="{{ route('admin.customers.index') }}" class="btn" style="background: #e2e8f0; color: var(--text-main); text-decoration: none; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.85rem; font-weight: 600;"><i class="fas fa-arrow-left"></i> Back to Customers</a>
        </div>
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order #</th>
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
                        <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                        <td style="font-weight: 600;">Rs.{{ number_format($order->total, 2) }}</td>
                        <td>
                            @php
                                $badgeClass = 'badge-yellow';
                                if($order->status == 'processing') $badgeClass = 'badge-blue';
                                if($order->status == 'delivered' || $order->status == 'completed') $badgeClass = 'badge-green';
                                if($order->status == 'refund_request' || $order->status == 'refunded') $badgeClass = 'badge-red';
                            @endphp
                            <span class="badge {{ $badgeClass }}" style="{{ $badgeClass == 'badge-red' ? 'background: #fef2f2; color: #ef4444;' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="view-all-btn" style="background: var(--primary); color: white; padding: 0.35rem 0.75rem; text-decoration: none;">View Order</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">This customer has not placed any orders yet.</td>
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

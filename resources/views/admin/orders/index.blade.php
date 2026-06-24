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
                        <th>Payment</th>
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
                            <div style="margin-bottom: 0.5rem; font-size: 0.85rem; font-weight: 500;">
                                {{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Credit/Debit Card' }}
                            </div>
                            <form action="{{ route('admin.orders.payment_status.update', $order) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="payment_status" onchange="this.form.submit()" style="padding: 0.25rem; border-radius: 4px; border: 1px solid var(--border-color); font-size: 0.8rem; background: {{ $order->payment_status == 'paid' ? '#f0fdf4' : ($order->payment_status == 'unpaid' ? '#fefce8' : '#fef2f2') }};">
                                    <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                            </form>
                        </td>
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
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.orders.show', $order) }}" class="view-all-btn" style="background: var(--primary); color: white; padding: 0.35rem 0.75rem; text-decoration: none; border-radius: 4px; font-size: 0.8rem;">View</a>
                                <a href="{{ route('admin.orders.print', $order) }}" target="_blank" class="view-all-btn" style="background: #10b981; color: white; padding: 0.35rem 0.75rem; text-decoration: none; border-radius: 4px; font-size: 0.8rem;"><i class="fas fa-print"></i> Invoice</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">No orders found.</td>
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

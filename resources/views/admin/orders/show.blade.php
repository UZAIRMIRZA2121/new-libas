@extends('layouts.admin')

@section('page_title', 'Order Details: ' . $order->order_number)

@section('content')
<div class="dashboard-grid">

    <div style="grid-column: span 12; display: flex; justify-content: flex-end; margin-bottom: -1rem;">
        <a href="{{ route('admin.orders.print', $order) }}" target="_blank" style="background: white; border: 1px solid #e2e8f0; padding: 0.5rem 1rem; border-radius: 6px; color: var(--text-main); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s;">
            <i class="fas fa-print"></i> Print Invoice
        </a>
    </div>

    @if(session('success'))
        <div style="grid-column: span 12; background: #dcfce7; color: #16a34a; padding: 1rem; border-radius: 8px; border: 1px solid #bbf7d0;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Left Column -->
    <div style="grid-column: span 8; display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- Items Card -->
        <div class="table-card">
            <div class="card-header">
                <h3>Items Ordered</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <div style="font-weight: 500;">{{ $item->product_name }}</div>
                            @if($item->color)
                            <div style="font-size: 0.75rem; color: var(--text-muted);">Color: {{ $item->color }}</div>
                            @endif
                        </td>
                        <td>Rs.{{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td style="text-align: right; font-weight: 600;">Rs.{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="padding: 1.5rem; display: flex; flex-direction: column; gap: 0.5rem; align-items: flex-end; border-top: 1px solid #f1f5f9;">
                <div style="display: flex; justify-content: space-between; width: 250px; color: var(--text-muted);">
                    <span>Subtotal</span>
                    <span>Rs.{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; width: 250px; color: var(--text-muted);">
                    <span>Shipping</span>
                    <span>{{ $order->shipping == 0 ? 'Free' : 'Rs.' . number_format($order->shipping, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; width: 250px; font-weight: 700; font-size: 1.25rem; border-top: 1px solid #f1f5f9; padding-top: 0.5rem; margin-top: 0.5rem;">
                    <span>Total</span>
                    <span>Rs.{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Right Column -->
    <div style="grid-column: span 4; display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- Status Card -->
        <div class="table-card">
            <div class="card-header">
                <h3>Update Order Status</h3>
            </div>
            <div style="padding: 1.5rem;">
                <form action="{{ route('admin.orders.status.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; color: var(--text-muted);">Current Status</label>
                        <select name="status" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="refund_request" {{ $order->status == 'refund_request' ? 'selected' : '' }}>Refund Request</option>
                            <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <button type="submit" style="width: 100%; background: var(--primary); color: white; border: none; padding: 0.75rem; border-radius: 8px; font-weight: 600; cursor: pointer;">Update Status</button>
                </form>

                @if($order->status == 'refund_request')
                <div style="margin-top: 1.5rem; padding: 1rem; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; color: #b91c1c;">
                    <h4 style="margin: 0 0 0.5rem 0; font-size: 0.875rem;">Refund Reason Provided:</h4>
                    <p style="margin: 0; font-size: 0.875rem; line-height: 1.5;">{{ $order->refund_reason }}</p>
                    
                    <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                        <form action="{{ route('admin.orders.status.update', $order) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Approve this refund?');">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="refunded">
                            <button type="submit" style="width: 100%; background: #16a34a; color: white; border: none; padding: 0.5rem; border-radius: 6px; font-weight: 500; cursor: pointer;">Approve Refund</button>
                        </form>
                        <form action="{{ route('admin.orders.status.update', $order) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Reject this refund and mark order as completed?');">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" style="width: 100%; background: #ef4444; color: white; border: none; padding: 0.5rem; border-radius: 6px; font-weight: 500; cursor: pointer;">Reject</button>
                        </form>
                    </div>
                </div>
                @elseif($order->refund_reason)
                <div style="margin-top: 1.5rem; padding: 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; color: #475569;">
                    <h4 style="margin: 0 0 0.5rem 0; font-size: 0.875rem;">Refund Reason Provided:</h4>
                    <p style="margin: 0; font-size: 0.875rem; line-height: 1.5;">{{ $order->refund_reason }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Customer Card -->
        <div class="table-card">
            <div class="card-header">
                <h3>Customer Details</h3>
            </div>
            <div style="padding: 1.5rem; font-size: 0.875rem; line-height: 1.6;">
                <div style="font-weight: 600; font-size: 1rem; margin-bottom: 0.5rem;">{{ $order->first_name }} {{ $order->last_name }}</div>
                <div><i class="fas fa-envelope" style="width: 20px; color: var(--text-muted);"></i> {{ $order->email }}</div>
                <div><i class="fas fa-phone" style="width: 20px; color: var(--text-muted);"></i> {{ $order->phone }}</div>
                
                <h4 style="margin-top: 1.5rem; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase;">Shipping Address</h4>
                <div>{{ $order->address }}</div>
                @if($order->apartment)<div>{{ $order->apartment }}</div>@endif
                <div>{{ $order->city }}, {{ $order->postal_code }}</div>
                
                <h4 style="margin-top: 1.5rem; margin-bottom: 0.5rem; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase;">Payment Method</h4>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    @if($order->payment_method == 'cod')
                        <i class="fas fa-money-bill-wave"></i> Cash on Delivery
                    @else
                        <i class="fas fa-credit-card"></i> Credit/Debit Card
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@extends('layouts.customer')
@section('customer_title', 'Order Details: ' . $order->order_number)

@section('customer_content')

@if(session('error'))
    <div style="background: #fef2f2; color: #ef4444; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
        {{ session('error') }}
    </div>
@endif
@if(session('success'))
    <div style="background: #dcfce7; color: #16a34a; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
@endif

<div style="background: #fff; border: 1px solid #f1f5f9; border-radius: 8px; padding: 2rem;">
    
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.5rem;">
        <div>
            <h3 style="margin: 0 0 0.5rem 0; font-size: 1.25rem;">Order Information</h3>
            <div style="color: #64748b; font-size: 0.9rem;">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</div>
        </div>
        <div style="text-align: left;">
            @if($order->status == 'delivered' && $order->delivered_at && $order->delivered_at->diffInDays(now()) <= 7)
                <form action="{{ route('customer.orders.refund', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to request a refund for this order?');">
                    @csrf
                    <textarea name="refund_reason" required placeholder="Please provide a reason for the refund..." style="width: 100%; max-width: 300px; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 6px; margin-bottom: 0.5rem; display: block; font-family: inherit; font-size: 0.85rem;" rows="2"></textarea>
                    <button type="submit" style="background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-undo"></i> Request Refund
                    </button>
                    <div style="font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem;">Valid until {{ $order->delivered_at->addDays(7)->format('M d') }}</div>
                </form>
            @elseif($order->status == 'refund_request')
                <span style="background: #fef2f2; color: #ef4444; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 500;"><i class="fas fa-clock"></i> Refund Requested</span>
            @elseif($order->status == 'refunded')
                <span style="background: #fef2f2; color: #ef4444; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 500;"><i class="fas fa-check-circle"></i> Refund Processed</span>
            @endif
        </div>
    </div>

    <!-- Items -->
    <h4 style="margin: 0 0 1rem 0; font-size: 1rem;">Items Ordered</h4>
    <div style="border: 1px solid #e2e8f0; border-radius: 8px; overflow-x: auto; margin-bottom: 2rem;">
        <table style="width: 100%; border-collapse: collapse; min-width: 500px;">
            <thead style="background: #f8fafc;">
                <tr>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.8rem; color: #64748b;">Product</th>
                    <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.8rem; color: #64748b;">Price</th>
                    <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.8rem; color: #64748b;">Qty</th>
                    <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.8rem; color: #64748b;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr style="border-top: 1px solid #f1f5f9;">
                    <td style="padding: 1rem;">
                        <div style="font-weight: 500;">{{ $item->product_name }}</div>
                        @if($item->color)<div style="font-size: 0.8rem; color: #64748b;">Color: {{ $item->color }}</div>@endif
                    </td>
                    <td style="padding: 1rem; text-align: right;">Rs.{{ number_format($item->price, 2) }}</td>
                    <td style="padding: 1rem; text-align: center;">{{ $item->quantity }}</td>
                    <td style="padding: 1rem; text-align: right; font-weight: 600;">Rs.{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Summary -->
    <div style="display: flex; justify-content: flex-end;">
        <div style="width: 300px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #64748b;">
                <span>Subtotal</span>
                <span>Rs.{{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: #64748b;">
                <span>Shipping</span>
                <span>{{ $order->shipping == 0 ? 'Free' : 'Rs.' . number_format($order->shipping, 2) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1.25rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
                <span>Total Amount</span>
                <span>Rs.{{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

</div>
@endsection

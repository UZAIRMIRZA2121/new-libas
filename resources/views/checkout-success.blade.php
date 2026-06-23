@extends('layouts.frontend')

@section('content')
<div style="max-width: 800px; margin: 4rem auto; text-align: center; padding: 2rem;">
    <div style="width: 80px; height: 80px; background: #dcfce7; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem;">
        <i class="fas fa-check"></i>
    </div>
    <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">Thank You For Your Order!</h1>
    <p style="font-size: 1.1rem; color: #64748b; margin-bottom: 2rem;">
        Your order <strong>{{ $order->order_number }}</strong> has been placed successfully.<br>
        We've sent a confirmation email to <strong>{{ $order->email }}</strong>.
    </p>

    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 2rem; text-align: left; margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Order Summary</h3>
        
        <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
            @foreach($order->items as $item)
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                <div>
                    <h4 style="font-size: 1rem; margin-bottom: 0.25rem;">{{ $item->product_name }}</h4>
                    @if($item->color)
                        <span style="font-size: 0.85rem; color: #64748b;">Color: {{ $item->color }}</span>
                    @endif
                    <div style="font-size: 0.85rem; color: #64748b;">Qty: {{ $item->quantity }}</div>
                </div>
                <div style="font-weight: 600;">
                    Rs.{{ number_format($item->price * $item->quantity, 2) }}
                </div>
            </div>
            @endforeach
        </div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #64748b;">
            <span>Subtotal</span>
            <span>Rs.{{ number_format($order->subtotal, 2) }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: #64748b;">
            <span>Shipping</span>
            <span>{{ $order->shipping == 0 ? 'Free' : 'Rs.' . number_format($order->shipping, 2) }}</span>
        </div>
        @if($order->discount_amount > 0)
        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: #10b981;">
            <span>Discount @if($order->coupon_code) <span style="font-size: 0.8rem; background:#10b981; color:white; padding:0.1rem 0.3rem; border-radius:4px;">{{ $order->coupon_code }}</span> @endif</span>
            <span>-Rs.{{ number_format($order->discount_amount, 2) }}</span>
        </div>
        @endif
        <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1.25rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
            <span>Total</span>
            <span>Rs.{{ number_format($order->total, 2) }}</span>
        </div>
    </div>

    <a href="{{ url('/') }}" style="display: inline-block; background: var(--primary-color); color: white; padding: 1rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600;">Continue Shopping</a>
</div>
@endsection

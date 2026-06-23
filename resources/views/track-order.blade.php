@extends('layouts.frontend')

@section('title', 'Track Your Order - New Libas')

@section('content')
<div class="container" style="max-width: 800px; padding: 4rem 1rem;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 1rem;">Track Your Order</h1>
        <p style="color: var(--text-muted); font-size: 1.1rem;">Enter your Order Number below to check the current status and details of your shipment.</p>
    </div>

    <!-- Search Form -->
    <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 2rem;">
        <form action="{{ route('track.order') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <input type="text" name="order_number" value="{{ request('order_number') }}" placeholder="e.g., ORD-9IYDZZO5EU" required style="width: 100%; padding: 1rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; outline: none;">
            </div>
            <button type="submit" style="background: var(--primary-color); color: white; border: none; padding: 1rem 2rem; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: background 0.2s;">
                <i class="fas fa-search"></i> Track
            </button>
        </form>
    </div>

    <!-- Error Message -->
    @if($error)
        <div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 1rem; color: #b91c1c; border-radius: 4px; margin-bottom: 2rem;">
            <i class="fas fa-exclamation-circle"></i> {{ $error }}
        </div>
    @endif

    <!-- Order Details -->
    @if($order)
        <div style="background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden;">
            <!-- Header -->
            <div style="background: #f8fafc; padding: 1.5rem 2rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.25rem;">Order #{{ $order->order_number }}</h2>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Placed on {{ $order->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    @php
                        $statusColors = [
                            'pending' => ['bg' => '#fefce8', 'text' => '#ca8a04'],
                            'processing' => ['bg' => '#eff6ff', 'text' => '#2563eb'],
                            'shipped' => ['bg' => '#f3e8ff', 'text' => '#9333ea'],
                            'delivered' => ['bg' => '#f0fdf4', 'text' => '#16a34a'],
                            'cancelled' => ['bg' => '#fef2f2', 'text' => '#dc2626'],
                        ];
                        $colors = $statusColors[$order->status] ?? ['bg' => '#f1f5f9', 'text' => '#475569'];
                    @endphp
                    <span style="background: {{ $colors['bg'] }}; color: {{ $colors['text'] }}; padding: 0.5rem 1rem; border-radius: 99px; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 1px;">
                        <i class="fas fa-circle" style="font-size: 0.5rem; vertical-align: middle; margin-right: 0.25rem;"></i> {{ $order->status }}
                    </span>
                </div>
            </div>

            <!-- Customer Details -->
            <div style="padding: 2rem; border-bottom: 1px solid #e2e8f0; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem;">
                <div>
                    <h3 style="font-size: 0.875rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Contact Info</h3>
                    <p style="font-weight: 500; color: var(--text-main); margin-bottom: 0.25rem;">{{ $order->first_name }} {{ $order->last_name }}</p>
                    <p style="color: #475569; font-size: 0.9rem; margin-bottom: 0.25rem;">{{ $order->email }}</p>
                    <p style="color: #475569; font-size: 0.9rem;">{{ $order->phone }}</p>
                </div>
                <div>
                    <h3 style="font-size: 0.875rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Shipping Address</h3>
                    <p style="color: #475569; font-size: 0.9rem; margin-bottom: 0.25rem;">{{ $order->address }}</p>
                    @if($order->apartment)
                        <p style="color: #475569; font-size: 0.9rem; margin-bottom: 0.25rem;">{{ $order->apartment }}</p>
                    @endif
                    <p style="color: #475569; font-size: 0.9rem;">{{ $order->city }}, {{ $order->postal_code }}</p>
                </div>
                <div>
                    <h3 style="font-size: 0.875rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Payment Method</h3>
                    <p style="font-weight: 500; color: var(--text-main);">
                        @if($order->payment_method === 'cod')
                            Cash on Delivery (COD)
                        @else
                            Credit/Debit Card
                        @endif
                    </p>
                </div>
            </div>

            <!-- Items -->
            <div style="padding: 2rem;">
                <h3 style="font-size: 1.1rem; font-weight: 600; color: var(--text-main); margin-bottom: 1rem;">Items Ordered</h3>
                <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem;">
                    @foreach($order->items as $item)
                        <div style="display: flex; justify-content: space-between; align-items: center; border: 1px solid #f1f5f9; padding: 1rem; border-radius: 8px;">
                            <div>
                                <h4 style="font-size: 1rem; font-weight: 500; color: var(--text-main); margin-bottom: 0.25rem;">{{ $item->product_name }}</h4>
                                @if($item->color)
                                    <span style="font-size: 0.85rem; color: #64748b; background: #f8fafc; padding: 0.2rem 0.5rem; border-radius: 4px; border: 1px solid #e2e8f0;">Color: {{ $item->color }}</span>
                                @endif
                                <div style="font-size: 0.9rem; color: #64748b; margin-top: 0.5rem;">Qty: {{ $item->quantity }} x Rs.{{ number_format($item->price, 2) }}</div>
                            </div>
                            <div style="font-weight: 600; color: var(--text-main);">
                                Rs.{{ number_format($item->price * $item->quantity, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Totals -->
                <div style="max-width: 300px; margin-left: auto;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #64748b;">
                        <span>Subtotal</span>
                        <span>Rs.{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #64748b;">
                        <span>Shipping</span>
                        <span>{{ $order->shipping == 0 ? 'Free' : 'Rs.' . number_format($order->shipping, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #10b981;">
                            <span>Discount @if($order->coupon_code) <span style="font-size: 0.75rem; background:#10b981; color:white; padding:0.1rem 0.3rem; border-radius:4px;">{{ $order->coupon_code }}</span> @endif</span>
                            <span>-Rs.{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 1.25rem; color: var(--text-main); border-top: 1px solid #e2e8f0; padding-top: 1rem; margin-top: 0.5rem;">
                        <span>Total</span>
                        <span>Rs.{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

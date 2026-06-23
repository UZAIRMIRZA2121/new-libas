@extends('layouts.frontend')

@section('content')
        @php 
        $cart = session('cart', []);
        $total = collect($cart)->sum(function($item) { return $item['price'] * $item['quantity']; });
        @endphp
        
        <div class="cart-header">
            <h2>Cart <span class="cart-count-badge">{{ collect($cart)->sum('quantity') }}</span></h2>
        </div>

        <div class="cart-layout">
            <!-- Left: Cart Items -->
            <div class="cart-items-section">
                @forelse($cart as $key => $item)
                <div class="cart-item">
                    <div class="cart-item-image">
                        <img src="{{ $item['image'] ? asset('storage/'.$item['image']) : 'https://placehold.co/200x200?text=No+Image' }}" alt="Product">
                    </div>
                    <div class="cart-item-details">
                        <h4 class="cart-item-title"><a href="{{ url('/product/'.\Illuminate\Support\Str::slug($item['name'])) }}" style="text-decoration: none; color: inherit;">{{ $item['name'] }}</a></h4>
                        @if($item['color'])
                            <p class="cart-item-subtitle">Color: {{ $item['color'] }}</p>
                        @endif
                        <div class="cart-item-price">
                            <span class="current">Rs.{{ number_format($item['price'], 2) }}</span>
                        </div>
                    </div>
                    <div class="cart-item-actions" style="display:flex; align-items:center; gap: 1rem;">
                        <form action="{{ route('cart.update') }}" method="POST" style="display:flex; align-items:center;">
                            @csrf
                            <input type="hidden" name="cart_key" value="{{ $key }}">
                            <div class="quantity-selector" style="width: 110px; height: 38px;">
                                <button type="button" class="qty-btn" style="width: 30px;" onclick="let inp = this.nextElementSibling; if(inp.value > 1) { inp.value--; inp.form.submit(); }"><i class="fas fa-minus" style="font-size:0.8rem;"></i></button>
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="qty-input" style="-moz-appearance: textfield;" onchange="this.form.submit()">
                                <button type="button" class="qty-btn" style="width: 30px;" onclick="let inp = this.previousElementSibling; inp.value++; inp.form.submit();"><i class="fas fa-plus" style="font-size:0.8rem;"></i></button>
                            </div>
                            <style>
                                .qty-input::-webkit-outer-spin-button,
                                .qty-input::-webkit-inner-spin-button {
                                    -webkit-appearance: none;
                                    margin: 0;
                                }
                            </style>
                        </form>
                        <form action="{{ route('cart.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="cart_key" value="{{ $key }}">
                            <button type="submit" class="delete-btn" style="background:none; border:none; color:#ef4444; font-size:1.1rem; cursor:pointer;"><i class="far fa-trash-alt"></i></button>
                        </form>
                    </div>
                    <div class="cart-item-subtotal">
                        Rs.{{ number_format($item['price'] * $item['quantity'], 2) }}
                    </div>
                </div>
                @empty
                <div style="padding: 2rem; text-align: center; color: #64748b;">
                    <p>Your cart is empty.</p>
                    <a href="{{ url('/') }}" style="display: inline-block; margin-top: 1rem; color: var(--primary-color); text-decoration: none;">Continue Shopping</a>
                </div>
                @endforelse
            </div>

            <!-- Right: Order Summary -->
            <div class="cart-summary-section">
                <div class="summary-box">
                    <div class="summary-row total-row">
                        <span>Estimated total</span>
                        <strong>Rs.{{ number_format($total, 2) }} PKR</strong>
                    </div>
                    
                    <p class="summary-tax-note">Taxes and shipping calculated at checkout.</p>
                    
                    <a href="{{ url('/checkout') }}" class="btn-checkout" style="display:block; text-align:center; text-decoration:none; {{ count($cart) == 0 ? 'opacity:0.5; pointer-events:none;' : '' }}">Check out</a>
                </div>
            </div>
        </div>

        <!-- You may also like -->
        <section class="section recommended-section">
            <div class="section-header">
                <h2 class="section-title">You may also like</h2>
                <a href="{{ url('/category') }}" class="view-all">View all</a>
            </div>
            
            <div class="product-grid deals-grid">
                @forelse($recommendedProducts as $product)
                <!-- Product Card -->
                <div class="product-card">
                    <div class="product-image">
                        @if($product->old_price && $product->old_price > $product->price)
                            @php $discount = round((($product->old_price - $product->price) / $product->old_price) * 100); @endphp
                            <span class="discount-badge">-{{ $discount }}%</span>
                        @endif
                        <img src="{{ $product->main_image_path ? asset('storage/' . $product->main_image_path) : 'https://placehold.co/400x400?text=No+Image' }}" alt="{{ $product->name }}">
                        <button class="wishlist-btn" onclick="toggleWishlist({{ $product->id }}, this)"><i class="{{ in_array($product->id, $wishlistIds) ? 'fas fa-heart' : 'far fa-heart' }}" {!! in_array($product->id, $wishlistIds) ? 'style="color: #ef4444;"' : '' !!}></i></button>
                    </div>
                    <div class="product-info">
                        <h4 class="product-title"><a href="{{ route('product.show', $product->slug) }}" style="text-decoration: none; color: inherit;">{{ \Illuminate\Support\Str::limit($product->name, 40) }}</a></h4>
                        <div class="product-rating">
                            @for($i=1; $i<=5; $i++)
                                @if($i <= floor($product->rating))
                                    <i class="fas fa-star" style="color: #fbbf24;"></i>
                                @elseif($i - 0.5 <= $product->rating)
                                    <i class="fas fa-star-half-alt" style="color: #fbbf24;"></i>
                                @else
                                    <i class="far fa-star" style="color: #fbbf24;"></i>
                                @endif
                            @endfor
                            <span>({{ $product->review_count }})</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">Rs.{{ number_format($product->price, 2) }}</span>
                            @if($product->old_price && $product->old_price > $product->price)
                                <span class="old-price">Rs.{{ number_format($product->old_price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <p style="grid-column: span 4; text-align: center; color: #64748b; padding: 2rem;">No recommendations available right now.</p>
                @endforelse
            </div>
        </section>
@endsection

@extends('layouts.checkout')

@section('content')
        @php 
        $cart = session('cart', []);
        $subtotal = collect($cart)->sum(function($item) { return $item['price'] * $item['quantity']; });
        
        $discountAmount = 0;
        if(session()->has('applied_coupon')) {
            $discountAmount = session('applied_coupon')['discount_amount'];
        }
        
        $total = $subtotal - $discountAmount; // Shipping is free for now

        $user = auth()->user();
        $nameParts = $user ? explode(' ', $user->name, 2) : [];
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';
        @endphp

        <div class="checkout-layout">
            
            <!-- Left: Checkout Forms -->
            <div class="checkout-form-section">
                
                @if ($errors->any())
                    <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #f87171;">
                        <ul style="margin-left: 1.5rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    
                    @guest
                    <div class="checkout-step">
                        <div class="step-header">
                            <h3>1. Contact Information</h3>
                            <p>Already have an account? <a href="{{ route('login') }}" class="text-primary">Log in</a></p>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" class="form-control" required>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="news-updates">
                            <label for="news-updates">Email me with news and offers</label>
                        </div>
                    </div>
                    @else
                    <div class="checkout-step">
                        <div class="step-header" style="margin-bottom: 0;">
                            <h3 style="margin-bottom: 0.5rem;">1. Contact Information</h3>
                            <div style="display: flex; align-items: center; gap: 0.5rem; color: #475569; font-size: 0.95rem;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="fas fa-user" style="color: #94a3b8;"></i>
                                    @endif
                                </div>
                                <div>
                                    <span style="font-weight: 500; color: #1e293b;">{{ auth()->user()->name }}</span>
                                    <span>({{ auth()->user()->email }})</span>
                                    <div style="font-size: 0.8rem; color: #10b981; margin-top: 0.2rem;"><i class="fas fa-check-circle"></i> Logged In</div>
                                </div>
                            </div>
                            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                        </div>
                    </div>
                    @endguest

                    <div class="checkout-step">
                        <div class="step-header">
                            <h3>2. Shipping Address</h3>
                        </div>
                        <div class="form-row">
                            <div class="form-group half-width">
                                <input type="text" name="first_name" value="{{ old('first_name', $firstName) }}" placeholder="First Name" class="form-control" required>
                            </div>
                            <div class="form-group half-width">
                                <input type="text" name="last_name" value="{{ old('last_name', $lastName) }}" placeholder="Last Name" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="address" value="{{ old('address', $user->address ?? '') }}" placeholder="Address" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="apartment" value="{{ old('apartment', $user->apartment ?? '') }}" placeholder="Apartment, suite, etc. (optional)" class="form-control">
                        </div>
                        <div class="form-row">
                            <div class="form-group half-width">
                                <input type="text" name="city" value="{{ old('city', $user->city ?? '') }}" placeholder="City" class="form-control" required>
                            </div>
                            <div class="form-group half-width">
                                <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code ?? '') }}" placeholder="Postal code" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="Phone" class="form-control" required>
                        </div>
                    </div>

                    <div class="checkout-step">
                        <div class="step-header">
                            <h3>3. Payment Method</h3>
                            <p>All transactions are secure and encrypted.</p>
                        </div>
                        
                        <div class="payment-methods-list">
                            <!-- Card Payment -->
                            <div class="payment-option">
                                <input type="radio" name="payment" value="card" id="pay-card">
                                <label for="pay-card" class="payment-label">
                                    <span>Credit / Debit Card</span>
                                    <div class="card-icons">
                                        <i class="fab fa-cc-visa"></i>
                                        <i class="fab fa-cc-mastercard"></i>
                                    </div>
                                </label>
                                <div class="payment-details">
                                    <div class="form-group">
                                        <input type="text" placeholder="Card number" class="form-control">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group half-width">
                                            <input type="text" placeholder="Expiration date (MM / YY)" class="form-control">
                                        </div>
                                        <div class="form-group half-width">
                                            <input type="text" placeholder="Security code" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" placeholder="Name on card" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- COD Payment -->
                            <div class="payment-option">
                                <input type="radio" name="payment" value="cod" id="pay-cod" checked>
                                <label for="pay-cod" class="payment-label">
                                    <span>Cash on Delivery (COD)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="checkout-actions">
                        <a href="{{ url('/cart') }}" class="back-link"><i class="fas fa-chevron-left"></i> Return to cart</a>
                        <button type="submit" class="btn-checkout-primary">Pay Rs.{{ number_format($total, 2) }}</button>
                    </div>
                </form>
            </div>

            <!-- Right: Order Summary -->
            <div class="checkout-summary-section">
                <div class="order-summary-box">
                    
                    <div class="summary-items">
                        @forelse($cart as $item)
                        <div class="summary-item">
                            <div class="summary-item-img">
                                <img src="{{ $item['image'] ? asset('storage/'.$item['image']) : 'https://placehold.co/100x100?text=No+Image' }}" alt="Product">
                                <span class="summary-qty-badge">{{ $item['quantity'] }}</span>
                            </div>
                            <div class="summary-item-info">
                                <h4>{{ $item['name'] }}</h4>
                                @if($item['color'])
                                    <span>Color: {{ $item['color'] }}</span>
                                @endif
                            </div>
                            <div class="summary-item-price">
                                Rs.{{ number_format($item['price'] * $item['quantity'], 2) }}
                            </div>
                        </div>
                        @empty
                        <div style="text-align:center; padding: 2rem; color: #64748b;">
                            Your cart is empty.
                        </div>
                        @endforelse
                    </div>

                    <div class="discount-code-input" id="coupon-input-group" style="display: {{ session('applied_coupon') ? 'none' : 'flex' }};">
                        <input type="text" id="coupon_code" placeholder="Discount code">
                        <button type="button" class="btn-apply" onclick="applyCoupon()" id="apply-coupon-btn">Apply</button>
                    </div>
                    <div id="coupon-message" style="font-size: 0.85rem; margin-top: 0.5rem; display: none;"></div>
                    
                    <div id="applied-coupon-group" style="display: {{ session('applied_coupon') ? 'flex' : 'none' }}; justify-content: space-between; align-items: center; background: #f0fdfa; border: 1px dashed #10b981; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                        <span style="font-weight: bold; color: #10b981;"><i class="fas fa-ticket-alt"></i> Code applied: <span id="applied-code-text">{{ session('applied_coupon')['code'] ?? '' }}</span></span>
                        <button type="button" onclick="removeCoupon()" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.85rem; text-decoration: underline;">Remove</button>
                    </div>

                    <div class="summary-totals">
                        <div class="summary-line">
                            <span>Subtotal</span>
                            <span>Rs.{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="summary-line" id="discount-line" style="display: {{ session('applied_coupon') ? 'flex' : 'none' }}; color: #10b981;">
                            <span>Discount <span id="discount-percentage-text" style="font-size: 0.8rem; background:#10b981; color:white; padding:0.1rem 0.3rem; border-radius:4px;">{{ session('applied_coupon')['percentage'] ?? '' }}%</span></span>
                            <span>-Rs.<span id="discount-amount-text">{{ number_format($discountAmount, 2) }}</span></span>
                        </div>
                        <div class="summary-line">
                            <span>Shipping</span>
                            <span class="free-shipping">Free</span>
                        </div>
                        <div class="summary-line total-line">
                            <span>Total</span>
                            <span class="total-price">Rs.<span id="total-price-text">{{ number_format($total, 2) }}</span> <span class="currency">PKR</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function applyCoupon() {
            const code = document.getElementById('coupon_code').value.trim();
            const emailInput = document.querySelector('input[name="email"]');
            const email = emailInput ? emailInput.value.trim() : '';
            const msgBox = document.getElementById('coupon-message');
            const btn = document.getElementById('apply-coupon-btn');

            if (!code) {
                msgBox.textContent = 'Please enter a coupon code.';
                msgBox.style.color = '#ef4444';
                msgBox.style.display = 'block';
                return;
            }

            if (!email && document.querySelector('.checkout-form-section input[name="email"]')) {
                msgBox.textContent = 'Please enter your email address first to verify coupon eligibility.';
                msgBox.style.color = '#ef4444';
                msgBox.style.display = 'block';
                // Highlight email field briefly
                emailInput.style.border = '2px solid #ef4444';
                setTimeout(() => emailInput.style.border = '1px solid #e2e8f0', 2000);
                return;
            }

            btn.disabled = true;
            btn.textContent = '...';

            fetch('{{ route("checkout.apply-coupon") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ code: code, email: email })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = 'Apply';
                
                if (data.success) {
                    document.getElementById('coupon-input-group').style.display = 'none';
                    msgBox.style.display = 'none';
                    
                    document.getElementById('applied-coupon-group').style.display = 'flex';
                    document.getElementById('applied-code-text').textContent = code.toUpperCase();
                    
                    document.getElementById('discount-line').style.display = 'flex';
                    document.getElementById('discount-percentage-text').textContent = data.percentage + '%';
                    
                    // Format numbers
                    const formatter = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    document.getElementById('discount-amount-text').textContent = formatter.format(data.discount_amount);
                    document.getElementById('total-price-text').textContent = formatter.format(data.new_total);
                    
                    // Update main button total
                    document.querySelector('.btn-checkout-primary').textContent = 'Pay Rs.' + formatter.format(data.new_total);
                } else {
                    msgBox.textContent = data.message;
                    msgBox.style.color = '#ef4444';
                    msgBox.style.display = 'block';
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.textContent = 'Apply';
                msgBox.textContent = 'An error occurred. Please try again.';
                msgBox.style.color = '#ef4444';
                msgBox.style.display = 'block';
            });
        }

        function removeCoupon() {
            fetch('{{ route("checkout.remove-coupon") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(() => {
                window.location.reload(); // Simplest way to reset totals cleanly
            });
        }
        </script>
@endsection

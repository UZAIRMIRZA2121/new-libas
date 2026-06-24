<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'New Libas - Premium Fashion')</title>
    <meta name="description" content="Discover the latest trends in fashion at New Libas. Shop for men, women, and kids.">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <!-- Header Section -->
    <header class="header">
        <div class="header-main container">
            <div class="logo">
                <a href="{{ url('/') }}" style="text-decoration: none; display: flex; align-items: center;">
                    <img src="{{ asset('storage/avatars/logo.png') }}" alt="New Libas Logo" style="height: 55px;">
                </a>
            </div>
            
            <div class="search-bar">
                <input type="text" placeholder="Search for products, brands and more...">
                <button class="search-btn"><i class="fas fa-search"></i></button>
            </div>

            <div class="header-actions">
               
                <a href="{{ route('track.order') }}" class="action-link" style="color: var(--primary-color); font-weight: 600;"><i class="fas fa-truck"></i> <span class="hide-mobile">Track Order</span></a>
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="action-link"><i class="fas fa-shield-alt"></i> <span class="hide-mobile">Admin</span></a>
                    @else
                        <a href="{{ route('customer.orders') }}" class="action-link"><i class="far fa-user"></i> <span class="hide-mobile">Orders</span></a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="action-link"><i class="fas fa-sign-out-alt"></i> <span class="hide-mobile">Logout</span></a>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="action-link"><i class="far fa-user"></i> <span class="hide-mobile">Sign in</span></a>
                    <a href="{{ route('register') }}" class="action-link"><i class="fas fa-user-plus"></i> <span class="hide-mobile">Register</span></a>
                @endauth
                <a href="{{ url('/cart') }}" class="action-cart">
                    <i class="fas fa-shopping-cart"></i>
                    @php $cartQty = collect(session('cart'))->sum('quantity'); @endphp
                    @if($cartQty > 0)
                        <span class="cart-badge">{{ $cartQty }}</span>
                    @endif
                </a>
            </div>
        </div>
        
        <nav class="main-nav">
            <div class="container">
                <ul class="nav-links">
                    <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ url('/category') }}" class="{{ request()->is('category*') ? 'active' : '' }}">All Categories <i class="fas fa-chevron-down"></i></a></li>
                    <li><a href="{{ url('/product') }}" class="{{ request()->is('product') ? 'active' : '' }}">Products</a></li>
                    <li><a href="#">Men</a></li>
                    <li><a href="#">Kids</a></li>
                    <li><a href="#">Beauty</a></li>
                    <li><a href="#">Accessories</a></li>
                    <li><a href="#">Sale <span class="badge-hot">Hot</span></a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <!-- Main Footer -->
    <footer class="dark-footer">
        <div class="container footer-content">
            <div class="footer-col brand-col">
                <div class="logo">
                    <a href="{{ url('/') }}" style="display: inline-block;">
                        <img src="{{ asset('storage/avatars/logo.png') }}" alt="New Libas Logo" style="height: 45px; display: block;">
                    </a>
                </div>
                <p class="brand-desc">Your one-stop destination for apparel, fashion, beauty, and more. Experience the best of online shopping with New Libas.</p>
                <div class="contact-info">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>D-Block, Ground Floor, Xenia Future Park, 10-KM Sheikhupura Road Faisalabad</p>
                </div>
            </div>
            
            <div class="footer-col links-col">
                <h3>New Libas</h3>
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/category') }}">All Categories</a></li>
                    <li><a href="{{ route('brand') }}">Top Brands</a></li>
                    <li><a href="#">Flash Sale</a></li>
                    <li><a href="#">Under 1000</a></li>
                </ul>
            </div>

            <div class="footer-col newsletter-col">
                <h3>Track Your Order</h3>
                <form action="{{ route('track.order') }}" method="GET" class="newsletter-form" style="margin-bottom: 1.5rem;">
                    <input type="text" name="order_number" placeholder="e.g. ORD-..." required>
                    <button type="submit">Track</button>
                </form>

                <h3>Newsletter</h3>
                <p>Subscribe for exclusive offers and updates.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Your email address">
                    <button type="submit">Subscribe</button>
                </form>
                <div class="social-icons">
                    <a href="#" class="social-icon youtube"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-icon facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon tiktok"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="social-icon instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon snapchat"><i class="fab fa-snapchat-ghost"></i></a>
                    <a href="#" class="social-icon pinterest"><i class="fab fa-pinterest-p"></i></a>
                    <a href="#" class="social-icon linkedin"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; 2026 New Libas. All rights reserved.</p>
                <div class="payment-methods">
                    <i class="fab fa-cc-mastercard"></i>
                    <i class="fab fa-cc-visa"></i>
                    <i class="fab fa-cc-paypal"></i>
                </div>
            </div>
        </div>
        </div>
    </footer>

    <!-- Welcome Coupon Modal -->
    <div id="welcomeCouponModal" class="coupon-modal-overlay">
        <div class="coupon-modal">
            <button class="close-modal" onclick="closeCouponModal()">&times;</button>
            <div class="coupon-content">
                <h2 style="color: var(--primary-color); font-weight: 800; font-size: 2rem; margin-bottom: 0.5rem;">Wait!</h2>
                <h3 style="font-size: 1.3rem; margin-bottom: 1rem;">Get <span style="color: var(--danger); font-weight: bold;">15% OFF</span> Your First Order!</h3>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Enter your email below to instantly receive your exclusive 15% discount code for all products.</p>
                
                <div id="couponFormArea">
                    <input type="email" id="couponEmail" placeholder="Enter your email address" style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px; margin-bottom: 1rem; outline: none;">
                    <button onclick="submitCouponEmail()" style="width: 100%; padding: 0.8rem; background: var(--primary-color); color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: background 0.3s;">GET MY 15% OFF</button>
                    <p id="couponError" style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem; display: none;"></p>
                </div>
                
                <div id="couponSuccessArea" style="display: none; background: #f0fdfa; border: 1px dashed var(--primary-color); padding: 1rem; border-radius: 8px;">
                    <p style="color: var(--primary-color); font-weight: bold; margin-bottom: 0.5rem;" id="successMsg">Welcome! Here is your 15% discount code:</p>
                    
                    <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                        <div style="flex: 1; background: white; padding: 0.8rem; border-radius: 6px; font-size: 1.2rem; font-weight: 800; letter-spacing: 2px; color: var(--text-main); user-select: all; border: 1px solid var(--border-color);" id="couponCodeDisplay">FIRSTORDER15</div>
                        <button id="copyCouponBtn" onclick="copyCouponCode()" style="padding: 0.8rem 1.2rem; background: var(--primary-color); color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: background 0.3s; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="far fa-copy"></i> <span>Copy</span>
                        </button>
                    </div>
                    
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.8rem;">Copy this code and apply it at checkout.</p>
                </div>
                
                <p style="margin-top: 1.5rem; font-size: 0.8rem; color: var(--text-muted); text-decoration: underline; cursor: pointer;" onclick="closeCouponModal()">No thanks, I prefer paying full price</p>
            </div>
        </div>
    </div>

    <style>
    .coupon-modal-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.4s ease;
    }
    .coupon-modal-overlay.show {
        opacity: 1;
        pointer-events: auto;
    }
    .coupon-modal {
        background: white;
        width: 90%;
        max-width: 450px;
        border-radius: 16px;
        padding: 2.5rem 2rem;
        position: relative;
        text-align: center;
        transform: translateY(20px);
        transition: transform 0.4s ease;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .coupon-modal-overlay.show .coupon-modal {
        transform: translateY(0);
    }
    .close-modal {
        position: absolute;
        top: 15px; right: 20px;
        background: none; border: none;
        font-size: 1.5rem; color: #94a3b8;
        cursor: pointer;
    }
    .close-modal:hover { color: #0f172a; }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (!localStorage.getItem('welcomeCouponSeen')) {
            setTimeout(() => {
                document.getElementById('welcomeCouponModal').classList.add('show');
            }, 3000);
        }
    });

    function closeCouponModal() {
        document.getElementById('welcomeCouponModal').classList.remove('show');
        
        // If the user hasn't successfully submitted their email,
        // show the modal again after 10 seconds.
        if (!localStorage.getItem('welcomeCouponSeen')) {
            setTimeout(() => {
                document.getElementById('welcomeCouponModal').classList.add('show');
            }, 10000);
        }
    }
    
    function copyCouponCode() {
        const codeText = document.getElementById('couponCodeDisplay').textContent;
        navigator.clipboard.writeText(codeText).then(() => {
            const copyBtn = document.getElementById('copyCouponBtn');
            copyBtn.innerHTML = '<i class="fas fa-check"></i> <span>Copied!</span>';
            copyBtn.style.background = 'var(--secondary-color)'; // Give it a nice secondary color to show it worked
            
            setTimeout(() => {
                copyBtn.innerHTML = '<i class="far fa-copy"></i> <span>Copy</span>';
                copyBtn.style.background = 'var(--primary-color)';
            }, 3000);
        });
    }

    function submitCouponEmail() {
        const email = document.getElementById('couponEmail').value;
        const errorMsg = document.getElementById('couponError');
        const submitBtn = event.target;
        
        if (!email || !email.includes('@')) {
            errorMsg.textContent = "Please enter a valid email address.";
            errorMsg.style.display = "block";
            return;
        }
        errorMsg.style.display = "none";
        submitBtn.disabled = true;
        submitBtn.textContent = "Processing...";
        
        fetch('{{ route("coupon.subscribe") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || data.message === "You already have a coupon!") {
                document.getElementById('couponFormArea').style.display = 'none';
                document.getElementById('couponSuccessArea').style.display = 'block';
                if(data.message) document.getElementById('successMsg').textContent = data.message;
                if(data.code) document.getElementById('couponCodeDisplay').textContent = data.code;
                localStorage.setItem('welcomeCouponSeen', 'true');
                localStorage.setItem('tracking_user_email', email);
                if (typeof trackEvent === 'function') {
                    trackEvent('email_submit', 'Coupon Form Submit', email);
                }
            } else {
                errorMsg.textContent = data.message || "An error occurred.";
                errorMsg.style.display = "block";
                submitBtn.disabled = false;
                submitBtn.textContent = "GET MY 15% OFF";
            }
        })
        .catch(error => {
            errorMsg.textContent = "Something went wrong. Please try again.";
            errorMsg.style.display = "block";
            submitBtn.disabled = false;
            submitBtn.textContent = "GET MY 15% OFF";
        });
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleWishlist(productId, btn) {
            @guest
                Swal.fire({
                    title: 'Sign In Required',
                    text: 'Please sign in to save products to your wishlist!',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Sign In',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: 'var(--primary-color)'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('login') }}";
                    }
                });
                return;
            @endguest

            fetch("{{ route('wishlist.toggle') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(res => res.json())
            .then(data => {
                const icon = btn.querySelector('i');
                if (data.status === 'added') {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    icon.style.color = '#ef4444';
                } else if (data.status === 'removed') {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    icon.style.color = '';
                }
            })
            .catch(error => {
                console.error('Error toggling wishlist:', error);
            });
        }
    </script>
    
    <!-- Tracking System -->
    <script>
        function uuidv4() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }

        let trackingSessionId = localStorage.getItem('tracking_session_id');
        if (!trackingSessionId) {
            trackingSessionId = uuidv4();
            localStorage.setItem('tracking_session_id', trackingSessionId);
        }

        function trackEvent(eventType, elementText = null, providedEmail = null) {
            let email = providedEmail || localStorage.getItem('tracking_user_email') || null;
            
            fetch('{{ route("track.activity") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    session_id: trackingSessionId,
                    event_type: eventType,
                    url: window.location.href,
                    element_text: elementText,
                    email: email
                })
            }).catch(e => console.log('Tracking error', e));
        }

        // Track Page View
        window.addEventListener('load', () => {
            trackEvent('page_view');
        });

        // Track Clicks
        document.body.addEventListener('click', (e) => {
            let target = e.target.closest('a, button, input[type="submit"]');
            if (target) {
                let text = target.innerText || target.value || target.title || target.name || target.className || 'Unknown Element';
                text = text.toString().trim().substring(0, 100);
                if(text) {
                    trackEvent('click', text);
                }
            }
        });
    </script>
</body>
</html>

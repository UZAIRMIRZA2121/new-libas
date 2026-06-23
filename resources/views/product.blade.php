@extends('layouts.frontend')

@section('content')
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="{{ url('/') }}">Home</a> <span>/</span>
            <a href="{{ url('/category') }}">{{ $product->category->name ?? 'Uncategorized' }}</a> <span>/</span>
            <span class="current">{{ $product->name }}</span>
        </nav>

        @if(session('success'))
            <div style="padding: 1rem; background: #dcfce7; color: #166534; border-radius: 8px; margin-top: 1rem;">
                {{ session('success') }}
                <a href="{{ route('cart') }}" style="font-weight:bold; margin-left:10px; text-decoration:underline;">View Cart</a>
            </div>
        @endif

        <!-- Product Details Wrapper -->
        <div class="product-details-wrap">
            <!-- Left: Gallery -->
            <div class="product-gallery">
                <div class="main-image-container">
                    @if($product->old_price && $product->old_price > $product->price)
                        @php $discount = round((($product->old_price - $product->price) / $product->old_price) * 100); @endphp
                        <span class="discount-badge">-{{ $discount }}% OFF</span>
                    @endif
                    <button class="nav-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
                    <img src="{{ $product->main_image_path ? asset('storage/' . $product->main_image_path) : 'https://placehold.co/800x800?text=No+Image' }}" alt="Main Image" class="main-img" id="main-product-image">
                    <button class="nav-btn next-btn"><i class="fas fa-chevron-right"></i></button>
                </div>
                
                @if($product->images->count() > 0)
                <div class="thumbnail-list">
                    <div class="thumbnail active" onclick="changeImage('{{ asset('storage/' . $product->main_image_path) }}', this)">
                        <img src="{{ asset('storage/' . $product->main_image_path) }}" alt="Main Thumb">
                    </div>
                    @foreach($product->images as $img)
                        <div class="thumbnail" onclick="changeImage('{{ asset('storage/' . $img->image_path) }}', this)">
                            <img src="{{ asset('storage/' . $img->image_path) }}" alt="Thumb">
                        </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Right: Info -->
            <div class="product-info-main">
                <h1 class="product-title-large">{{ $product->name }}</h1>
                
                <div class="rating-reviews">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <span>{{ $product->rating }} ({{ $product->review_count }} Reviews)</span>
                    </div>
                    <div class="bought-count">
                        <i class="fas fa-fire"></i> {{ $product->bought_count }}+ Bought
                    </div>
                </div>

                <div class="product-price-large">
                    <span class="current">Rs.{{ number_format($product->price, 2) }}</span>
                    @if($product->old_price && $product->old_price > $product->price)
                        <span class="old">Rs.{{ number_format($product->old_price, 2) }}</span>
                    @endif
                </div>

                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    @if($product->colors->count() > 0)
                    <div class="product-color">
                        <span class="label">Color: <strong id="selected-color-label">Select a color</strong></span>
                        <input type="hidden" name="color" id="selected-color-input" required>
                        <div class="color-options">
                            @foreach($product->colors as $color)
                                <button type="button" class="color-btn" style="background-color: {{ $color->hex_code }};" title="{{ $color->name }}" onclick="selectColor('{{ $color->name }}', this)"></button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="stock-warning">
                        <i class="fas fa-exclamation-circle text-warning"></i>
                        <div class="warning-text">
                            @if($product->stock > 10)
                                <strong>In Stock</strong>
                                <p>Ready to ship immediately.</p>
                            @elseif($product->stock > 0)
                                <strong>Only {{ $product->stock }} left</strong>
                                <p>Selling fast! Order now to avoid missing out.</p>
                            @else
                                <strong style="color: var(--danger);">Out of Stock</strong>
                                <p>This item is currently unavailable.</p>
                            @endif
                        </div>
                    </div>

                    @if($product->stock > 0)
                    <div class="quantity-selector" style="margin-top: 1.5rem;">
                        <button type="button" class="qty-btn" onclick="updateQty(-1)"><i class="fas fa-minus"></i></button>
                        <input type="text" name="quantity" id="qty-input" value="1" readonly class="qty-input">
                        <button type="button" class="qty-btn" onclick="updateQty(1)"><i class="fas fa-plus"></i></button>
                    </div>

                    <div class="action-buttons" style="margin-top: 1.5rem;">
                        <button type="submit" class="btn-outline-primary" style="display:inline-flex; align-items:center; border: 2px solid var(--primary-color); background:transparent; cursor:pointer;"><i class="fas fa-shopping-cart" style="margin-right:0.5rem;"></i> Add to Cart</button>
                        <button type="button" class="btn-primary-large" onclick="document.forms[0].submit()" style="display:inline-flex; align-items:center; border:none; cursor:pointer;"><i class="fas fa-bolt" style="margin-right:0.5rem;"></i> Buy Now</button>
                    </div>
                    @endif
                </form>

          <div class="whatsapp-help" style="margin-top: 2rem;">
    <a href="https://wa.me/923086452242?text=Hi%20I%20am%20interested%20in%20this%20product:%20{{$product->name}}"
       target="_blank"
       style="text-decoration:none; color:inherit;">
        <i class="fab fa-whatsapp"></i> Need help? Chat on WhatsApp
    </a>
</div>

                <div class="service-badges">
                    <div class="badge-item">
                        <i class="fas fa-shield-alt text-teal"></i>
                        <div><strong>Verified Seller</strong><span>Since 2020</span></div>
                    </div>
                    <div class="badge-item">
                        <i class="fas fa-medal text-teal"></i>
                        <div><strong>100% Authentic</strong><span>Products</span></div>
                    </div>
                    <div class="badge-item">
                        <i class="fas fa-box-open text-teal"></i>
                        <div><strong>Shipping & Returns</strong><span>Within 14 days</span></div>
                    </div>
                    <div class="badge-item">
                        <i class="fas fa-headset text-teal"></i>
                        <div><strong>24/7 Customer</strong><span>Support</span></div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            function selectColor(name, btn) {
                document.getElementById('selected-color-label').innerText = name;
                document.getElementById('selected-color-input').value = name;
                document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            }
            function updateQty(change) {
                let input = document.getElementById('qty-input');
                let val = parseInt(input.value) + change;
                if(val < 1) val = 1;
                @if($product->stock > 0)
                if(val > {{ $product->stock }}) val = {{ $product->stock }};
                @endif
                input.value = val;
            }
            function changeImage(src, thumb) {
                document.getElementById('main-product-image').src = src;
                document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');
            }
        </script>

        <!-- Product Description & Specs -->
        <div class="product-details-tabs">
            <div class="tabs-header">
                <button class="tab-btn active">Product Description</button>
                <button class="tab-btn">Reviews</button>
            </div>
            
            <div class="tab-content active">
                <p>{!! nl2br(e($product->description)) !!}</p>
                
                @if($product->specifications->count() > 0)
                <h4 class="specs-title">Specifications</h4>
                <table class="specs-table">
                    <tbody>
                        @foreach($product->specifications as $spec)
                        <tr>
                            <td style="text-transform: uppercase;">{{ $spec->key }}</td>
                            <td>{{ $spec->value }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

        <!-- Customer Reviews -->
        <div class="customer-reviews-section">
            <h3 class="section-title">Customer Reviews</h3>
            <div class="reviews-empty">
                <p>Be the first to write a review</p>
                <button class="btn-warning">Write a review</button>
                <p class="no-items">No items found</p>
            </div>
        </div>

        <!-- You may also like -->
        <section class="section recommended-section">
            <h2 class="section-title">You may also <span class="text-secondary">like</span></h2>
            <div class="product-grid deals-grid">
                @forelse($recommendedProducts as $recProduct)
                <!-- Card -->
                <div class="product-card">
                    <div class="product-image">
                        @if($recProduct->old_price && $recProduct->old_price > $recProduct->price)
                            @php $discount = round((($recProduct->old_price - $recProduct->price) / $recProduct->old_price) * 100); @endphp
                            <span class="discount-badge">-{{ $discount }}% OFF</span>
                        @endif
                        <img src="{{ $recProduct->main_image_path ? asset('storage/' . $recProduct->main_image_path) : 'https://placehold.co/400x400?text=No+Image' }}" alt="{{ $recProduct->name }}">
                        <button class="wishlist-btn" onclick="toggleWishlist({{ $recProduct->id }}, this)"><i class="{{ in_array($recProduct->id, $wishlistIds) ? 'fas fa-heart' : 'far fa-heart' }}" {!! in_array($recProduct->id, $wishlistIds) ? 'style="color: #ef4444;"' : '' !!}></i></button>
                    </div>
                    <div class="product-info">
                        <h4 class="product-title"><a href="{{ route('product.show', $recProduct->slug) }}" style="text-decoration: none; color: inherit;">{{ \Illuminate\Support\Str::limit($recProduct->name, 40) }}</a></h4>
                        <div class="product-rating">
                            @for($i=1; $i<=5; $i++)
                                @if($i <= floor($recProduct->rating))
                                    <i class="fas fa-star" style="color: #fbbf24;"></i>
                                @elseif($i - 0.5 <= $recProduct->rating)
                                    <i class="fas fa-star-half-alt" style="color: #fbbf24;"></i>
                                @else
                                    <i class="far fa-star" style="color: #fbbf24;"></i>
                                @endif
                            @endfor
                            <span>{{ $recProduct->rating ?? '0.0' }}</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">Rs.{{ number_format($recProduct->price, 2) }}</span>
                            @if($recProduct->old_price && $recProduct->old_price > $recProduct->price)
                                <span class="old-price">Rs.{{ number_format($recProduct->old_price, 2) }}</span>
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

@extends('layouts.frontend')

@section('content')
        <!-- Shop by Category -->
        <section class="section category-section">
            <h2 class="section-title">Shop By Category</h2>
            <div class="category-list">
                @forelse($categories as $category)
                    <div class="category-item">
                        <a href="{{ route('category', ['cat_id' => $category->id]) }}" style="text-decoration: none; color: inherit;">
                            <div class="category-img">
                                @if($category->image_path)
                                    <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}">
                                @else
                                    <div style="width: 100%; height: 100%; background: #e2e8f0; display: flex; align-items: center; justify-content: center;"><i class="fas fa-image" style="font-size: 2rem; color: #94a3b8;"></i></div>
                                @endif
                            </div>
                            <span>{{ $category->name }}</span>
                        </a>
                    </div>
                @empty
                    <p style="grid-column: span 12; text-align: center; color: #64748b; padding: 2rem;">No categories available yet.</p>
                @endforelse
            </div>
        </section>

        <style>
            .slider-container { position: relative; overflow: hidden; }
            .slider-btn { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.3); color: white; border: none; width: 40px; height: 40px; cursor: pointer; font-size: 1.2rem; border-radius: 50%; z-index: 10; transition: background 0.3s; display: flex; align-items: center; justify-content: center;}
            .slider-btn:hover { background: rgba(0,0,0,0.8); }
            .prev-btn { left: 20px; }
            .next-btn { right: 20px; }
            .slide { animation: fadeEffect 0.8s; }
            @keyframes fadeEffect { from {opacity: 0.4;} to {opacity: 1;} }
        </style>

        <!-- Hero Banner Slider -->
        <section class="banner-section slider-container">
            @forelse($banners as $index => $banner)
                <div class="hero-banner slide" style="display: {{ $index === 0 ? 'flex' : 'none' }};">
                    <img src="{{ Storage::url($banner->image_path) }}" alt="{{ $banner->title }}">
                    <div class="banner-content">
                        @if($banner->subtitle)<h3>{{ $banner->subtitle }}</h3>@endif
                        @if($banner->title)<h2>{{ $banner->title }}</h2>@endif
                        @if($banner->description)<p>{{ $banner->description }}</p>@endif
                        @if($banner->button_text)
                            <a href="{{ $banner->button_link }}" class="btn-primary">{{ $banner->button_text }}</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="hero-banner slide" style="display: flex;">
                    <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1200&q=80" alt="Fashion Sale Banner">
                    <div class="banner-content">
                        <h3>Welcome to New Libas</h3>
                        <h2>Premium Collection</h2>
                        <p>Discover styles that define you.</p>
                        <a href="{{ url('/product') }}" class="btn-primary">Shop Now</a>
                    </div>
                </div>
            @endforelse

            @if(isset($banners) && $banners->count() > 1)
                <button class="slider-btn prev-btn" onclick="moveSlide(-1)"><i class="fas fa-chevron-left"></i></button>
                <button class="slider-btn next-btn" onclick="moveSlide(1)"><i class="fas fa-chevron-right"></i></button>
            @endif
        </section>

        <script>
            let currentSlide = 0;
            const slides = document.querySelectorAll('.slide');

            function moveSlide(direction) {
                if(slides.length <= 1) return;
                
                slides[currentSlide].style.display = 'none';
                currentSlide = (currentSlide + direction + slides.length) % slides.length;
                slides[currentSlide].style.display = 'flex';
            }

            // Auto slide every 5 seconds
            if(slides.length > 1) {
                setInterval(() => moveSlide(1), 5000);
            }
        </script>

        <!-- Shop by Brands -->
        <section class="section brands-section">
            <h2 class="section-title">Shop By Brands</h2>
            <div style="position: relative; display: flex; align-items: center;">
                <button class="slider-btn prev-btn" onclick="scrollBrands(-1)" style="left: -20px; background: white; color: var(--primary-color); border: 1px solid var(--border-color); box-shadow: 0 2px 5px rgba(0,0,0,0.1); width: 35px; height: 35px;"><i class="fas fa-chevron-left"></i></button>
                <div class="brands-list" id="brandsList" style="flex: 1;">
                    @forelse($brands as $brand)
                        <div class="brand-item" style="padding: 1rem; display: flex; align-items: center; justify-content: center; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); min-height: 80px; transition: transform 0.2s;">
                            <a href="{{ route('brand', ['brand_id' => $brand->id]) }}" style="display: flex; width: 100%; height: 100%; align-items: center; justify-content: center; text-decoration: none;">
                                @if($brand->logo_path)
                                    <img src="{{ Storage::url($brand->logo_path) }}" alt="{{ $brand->name }}" style="max-width: 100%; max-height: 50px; object-fit: contain;">
                                @else
                                    <strong style="color: #475569;">{{ $brand->name }}</strong>
                                @endif
                            </a>
                        </div>
                    @empty
                        <p style="grid-column: span 12; text-align: center; color: #64748b; padding: 2rem;">No brands available yet.</p>
                    @endforelse
                </div>
                <button class="slider-btn next-btn" onclick="scrollBrands(1)" style="right: -20px; background: white; color: var(--primary-color); border: 1px solid var(--border-color); box-shadow: 0 2px 5px rgba(0,0,0,0.1); width: 35px; height: 35px;"><i class="fas fa-chevron-right"></i></button>
            </div>
            <script>
                function scrollBrands(direction) {
                    const list = document.getElementById('brandsList');
                    list.scrollBy({ left: direction * 300, behavior: 'smooth' });
                }
            </script>
        </section>

        <!-- Deals of the Day -->
        <section class="section deals-section">
            <div class="section-header">
                <h2 class="section-title">Deals of the Day <span class="timer">Ends in 05:40:00</span></h2>
                <a href="{{ url('/category') }}" class="view-all">View All</a>
            </div>
            <div class="product-grid deals-grid">
                @forelse($featuredProducts as $product)
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
                <p style="grid-column: span 4; text-align: center; color: #64748b; padding: 2rem;">No deals available at the moment.</p>
                @endforelse
            </div>
        </section>

        <!-- Recommended For You -->
        <section class="section recommended-section">
            <h2 class="section-title">Recommended For You</h2>
            <div class="product-grid">
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
                <p style="grid-column: span 4; text-align: center; color: #64748b; padding: 2rem;">No recommendations available.</p>
                @endforelse
            </div>
        </section>
@endsection

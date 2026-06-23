@extends('layouts.frontend')

@section('content')
    @if($currentBrand)
        <!-- Brand Banner Section -->
        <div class="brand-banner" style="position: relative; border-radius: 12px; overflow: hidden; margin-bottom: 2rem; background: #000;">
            @if($currentBrand->banner_path)
                <img src="{{ Storage::url($currentBrand->banner_path) }}" alt="{{ $currentBrand->name }} Banner" style="width: 100%; height: 250px; object-fit: cover; opacity: 0.8;">
            @else
                <img src="https://placehold.co/1200x300/1e293b/ffffff?text={{ urlencode($currentBrand->name) }}+Banner" alt="Banner" style="width: 100%; height: 250px; object-fit: cover; opacity: 0.6;">
            @endif
            <div style="position: absolute; top: 50%; left: 50px; transform: translateY(-50%); display: flex; align-items: center; gap: 1.5rem;">
                <div style="width: 100px; height: 100px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.3);">
                    @if($currentBrand->logo_path)
                        <img src="{{ Storage::url($currentBrand->logo_path) }}" alt="{{ $currentBrand->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    @else
                        <strong style="font-size: 1.5rem;">{{ substr($currentBrand->name, 0, 1) }}</strong>
                    @endif
                </div>
                <div>
                    <h1 style="color: white; margin: 0; font-size: 2.5rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">{{ $currentBrand->name }} <i class="fas fa-check-circle" style="color: #3b82f6; font-size: 1.5rem;"></i></h1>
                    <p style="color: #e2e8f0; margin: 0.5rem 0 0 0; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">Innovation in Every Detail</p>
                </div>
            </div>
        </div>

        <!-- About Brand -->
        <div style="margin-bottom: 2rem;">
            <h4 style="color: var(--primary); text-transform: uppercase; font-size: 0.9rem; margin-bottom: 0.5rem; font-weight: 600;">About the Brand</h4>
            <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">{{ $currentBrand->name }} is a leading lifestyle brand delivering innovative solutions and smart technology for modern living.</p>
        </div>
        
        <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <p style="color: var(--text-muted); font-size: 0.9rem;">Showing {{ $products->count() }} products</p>
        </div>
    @else
        <!-- Shop By Brands Title -->
        <div class="category-page-header" style="margin-bottom: 2rem;">
            <h2>All Brands</h2>
        </div>
        
        <!-- Brands List -->
        <div class="brands-list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 3rem;">
            @forelse($brands as $brand)
                <a href="{{ route('brand', ['brand_id' => $brand->id]) }}" style="text-decoration: none; color: inherit; display: block;">
                    <div class="brand-item" style="padding: 1rem; display: flex; align-items: center; justify-content: center; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); min-height: 80px; transition: transform 0.2s;">
                        @if($brand->logo_path)
                            <img src="{{ Storage::url($brand->logo_path) }}" alt="{{ $brand->name }}" style="max-width: 100%; max-height: 50px; object-fit: contain;">
                        @else
                            <strong style="color: #475569;">{{ $brand->name }}</strong>
                        @endif
                    </div>
                </a>
            @empty
                <p>No brands available.</p>
            @endforelse
        </div>
    @endif

    @if($currentBrand || request()->routeIs('brand') && !$currentBrand)
        <!-- Products Grid (Dense 6-Column Layout) -->
        <div class="product-grid grid-6-cols">
            @forelse($products as $product)
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
                @if($currentBrand)
                <p style="grid-column: span 6; text-align: center; color: #64748b; padding: 2rem;">No products found in this brand.</p>
                @endif
            @endforelse
        </div>
    @endif
@endsection

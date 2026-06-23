@extends('layouts.frontend')
@section('content')
        <!-- Shop By Category Title -->
        <div class="category-page-header">
            <h2>Shop by Category</h2>
        </div>

        <!-- Main Categories Row -->
        <div class="category-circles-scroll">
            @forelse($categories as $category)
            <div class="category-item {{ $currentParentId == $category->id ? 'active' : '' }}" onclick="window.location='{{ route('category', ['cat_id' => $category->id]) }}'" style="cursor: pointer;">
                <div class="category-img">
                    @if($category->image_path)
                        <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}">
                    @else
                        <div style="width: 100%; height: 100%; background: #e2e8f0; display: flex; align-items: center; justify-content: center;"><i class="fas fa-image" style="font-size: 2rem; color: #94a3b8;"></i></div>
                    @endif
                </div>
                <span>{{ $category->name }}</span>
            </div>
            @empty
                <p style="padding: 1rem; color: #64748b;">No categories available.</p>
            @endforelse
        </div>

        <!-- Sub Categories -->
        @if($subCategories->isNotEmpty())
        <div class="sub-categories-nav">
            @foreach($subCategories as $subCat)
                <a href="{{ route('category', ['cat_id' => $subCat->id]) }}" class="{{ $cat_id == $subCat->id ? 'active' : '' }}">{{ $subCat->name }}</a>
            @endforeach
        </div>
        @endif

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
                        <span>({{ $product->review_count ?? 0 }})</span>
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
            <p style="grid-column: span 6; text-align: center; color: #64748b; padding: 2rem;">No products available for this category.</p>
            @endforelse
        </div>
@endsection

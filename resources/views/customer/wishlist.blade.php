@extends('layouts.customer')

@section('customer_title', 'My Wish List')

@section('customer_content')
<div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    @if($wishlists->count() > 0)
        <div class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
            @foreach($wishlists as $wishlist)
                @php $product = $wishlist->product; @endphp
                @if($product)
                <div class="product-card" style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; position: relative;">
                    <div class="product-image" style="position: relative; padding-bottom: 100%;">
                        <img src="{{ $product->main_image_path ? asset('storage/' . $product->main_image_path) : 'https://placehold.co/400x400?text=No+Image' }}" alt="{{ $product->name }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                        <button class="wishlist-btn" onclick="toggleWishlist({{ $product->id }}, this)" style="position: absolute; top: 10px; right: 10px; background: white; border: none; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); cursor: pointer; z-index: 10;">
                            <i class="fas fa-heart" style="color: #ef4444; font-size: 1.1rem;"></i>
                        </button>
                    </div>
                    <div class="product-info" style="padding: 1rem;">
                        <h4 style="font-size: 0.95rem; margin-bottom: 0.5rem;"><a href="{{ route('product.show', $product->slug) }}" style="text-decoration: none; color: inherit;">{{ \Illuminate\Support\Str::limit($product->name, 40) }}</a></h4>
                        <div style="font-weight: 600; color: var(--primary-color); margin-bottom: 1rem;">
                            Rs.{{ number_format($product->price, 2) }}
                        </div>
                        <form action="{{ route('cart.add') }}" method="POST" style="width: 100%;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            @if($product->colors->count() > 0)
                                <input type="hidden" name="color" value="{{ $product->colors->first()->name }}">
                            @endif
                            <button type="submit" style="width: 100%; padding: 0.6rem; background: var(--primary-color); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 0.9rem;">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 4rem 1rem;">
            <i class="far fa-heart" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 1.5rem;"></i>
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem;">Your wishlist is empty</h3>
            <p style="color: #64748b; margin-bottom: 1.5rem;">Save items you love so you can easily find them later.</p>
            <a href="{{ url('/') }}" style="display: inline-block; padding: 0.75rem 1.5rem; background: var(--primary-color); color: white; border-radius: 8px; text-decoration: none; font-weight: 500;">Start Shopping</a>
        </div>
    @endif
</div>
@endsection

@extends('layouts.admin')

@section('page_title', 'Products')

@section('content')
<div class="table-card">
    <div class="card-header">
        <h3>All Products</h3>
        <a href="{{ route('admin.products.create') }}" class="view-all-btn" style="background: var(--primary); color: white; padding: 0.5rem 1rem; border-radius: 8px;">Add New Product</a>
    </div>
    
    @if(session('success'))
        <div style="padding: 1rem; background: #dcfce7; color: #166534; border-radius: 8px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <th style="padding: 1rem;">Image</th>
                    <th style="padding: 1rem;">Name & SKU</th>
                    <th style="padding: 1rem;">Price</th>
                    <th style="padding: 1rem;">Stock</th>
                    <th style="padding: 1rem;">Status</th>
                    <th style="padding: 1rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 1rem;">
                        @if($product->main_image_path)
                            <img src="{{ asset('storage/' . $product->main_image_path) }}" alt="Product" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                        @else
                            <div style="width: 50px; height: 50px; background: #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748b;">
                                <i class="fas fa-box"></i>
                            </div>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <strong style="display: block;">{{ $product->name }}</strong>
                        <small style="color: var(--text-muted);">SKU: {{ $product->sku ?? 'N/A' }} | Cat: {{ $product->category->name ?? 'None' }}</small>
                    </td>
                    <td style="padding: 1rem;">
                        <strong style="color: var(--danger);">${{ number_format($product->price, 2) }}</strong>
                        @if($product->old_price)
                            <br><small style="text-decoration: line-through; color: var(--text-muted);">${{ number_format($product->old_price, 2) }}</small>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <span style="padding: 0.3rem 0.6rem; border-radius: 50px; font-size: 0.8rem; {{ $product->stock > 10 ? 'background: #dcfce7; color: #166534;' : 'background: #fee2e2; color: #991b1b;' }}">
                            {{ $product->stock }} in stock
                        </span>
                    </td>
                    <td style="padding: 1rem;">
                        @if($product->is_active)
                            <span style="background: #dcfce7; color: #166534; padding: 0.3rem 0.8rem; border-radius: 50px; font-size: 0.85rem;">Active</span>
                        @else
                            <span style="background: #fee2e2; color: #991b1b; padding: 0.3rem 0.8rem; border-radius: 50px; font-size: 0.85rem;">Hidden</span>
                        @endif
                        @if($product->is_featured)
                            <br><span style="background: #fef3c7; color: #92400e; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; margin-top: 5px; display: inline-block;"><i class="fas fa-star"></i> Featured</span>
                        @endif
                    </td>
                    <td style="padding: 1rem;">
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.products.edit', $product) }}" style="color: var(--primary);"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?');">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer;"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 2rem; text-align: center; color: var(--text-muted);">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

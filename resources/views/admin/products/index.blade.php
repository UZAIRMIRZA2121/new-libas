@extends('layouts.admin')

@section('page_title', 'Products')

@section('content')
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 24px;
}
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}
.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}
input:checked + .slider {
  background-color: #22c55e;
}
input:focus + .slider {
  box-shadow: 0 0 1px #22c55e;
}
input:checked + .slider:before {
  -webkit-transform: translateX(20px);
  -ms-transform: translateX(20px);
  transform: translateX(20px);
}
.slider.round {
  border-radius: 24px;
}
.slider.round:before {
  border-radius: 50%;
}
</style>
<div class="table-card">
    <div class="card-header" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: space-between;">
        <h3 style="margin: 0;">All Products</h3>
        <div style="display: flex; gap: 1rem; align-items: center; flex: 1; justify-content: flex-end;">
            <form action="{{ route('admin.products.index') }}" method="GET" style="display: flex; align-items: center;">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search name or SKU..." style="padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 8px 0 0 8px; outline: none; min-width: 200px;">
                <button type="submit" style="background: var(--primary); color: white; border: none; padding: 0.5rem 1rem; border-radius: 0 8px 8px 0; cursor: pointer;"><i class="fas fa-search"></i></button>
            </form>
            <a href="{{ route('admin.products.create') }}" class="view-all-btn" style="background: var(--primary); color: white; padding: 0.5rem 1rem; border-radius: 8px;">Add New Product</a>
        </div>
    </div>
    
    <div id="bulk-actions" style="display: none; padding: 1rem; background: #f8fafc; border-bottom: 1px solid var(--border-color); align-items: center; gap: 1rem;">
        <span id="selected-count" style="font-weight: 600; color: var(--text-main);">0 selected</span>
        <button onclick="openSkuModal()" style="background: white; border: 1px solid var(--border-color); color: var(--text-main); padding: 0.4rem 0.8rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem;"><i class="fas fa-edit"></i> Update SKUs</button>
        <button onclick="bulkDelete()" style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.4rem 0.8rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem;"><i class="fas fa-trash"></i> Delete Selected</button>
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
                    <th style="padding: 1rem; width: 40px;"><input type="checkbox" id="select-all" onclick="toggleAll(this)"></th>
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
                    <td style="padding: 1rem;"><input type="checkbox" class="product-checkbox" value="{{ $product->id }}" data-sku="{{ $product->sku }}" data-name="{{ $product->name }}" onchange="updateBulkActions()"></td>
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
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <label class="switch" title="Toggle Active Status">
                                <input type="checkbox" onchange="toggleStatus({{ $product->id }}, this)" {{ $product->is_active ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <span style="font-size: 0.85rem; color: var(--text-muted);">Active</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <label class="switch" title="Toggle Featured Status">
                                <input type="checkbox" onchange="toggleFeatured({{ $product->id }}, this)" {{ $product->is_featured ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <span style="font-size: 0.85rem; color: var(--text-muted);"><i class="fas fa-star" style="color: #f59e0b;"></i> Featured</span>
                        </div>
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
                    <td colspan="7" style="padding: 2rem; text-align: center; color: var(--text-muted);">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="skuModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 12px; width: 90%; max-width: 500px; max-height: 80vh; overflow-y: auto;">
        <h3 style="margin-top: 0; margin-bottom: 1rem;">Update SKUs</h3>
        <div id="sku-inputs-container"></div>
        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
            <button type="button" onclick="closeSkuModal()" style="padding: 0.5rem 1rem; background: #e2e8f0; border: none; border-radius: 6px; cursor: pointer;">Cancel</button>
            <button type="button" onclick="saveSkus()" style="padding: 0.5rem 1rem; background: var(--primary); color: white; border: none; border-radius: 6px; cursor: pointer;">Save SKUs</button>
        </div>
    </div>
</div>

<script>
function toggleAll(source) {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    checkboxes.forEach(cb => cb.checked = source.checked);
    updateBulkActions();
}

function updateBulkActions() {
    const checked = document.querySelectorAll('.product-checkbox:checked');
    const bulkBar = document.getElementById('bulk-actions');
    const countSpan = document.getElementById('selected-count');
    
    if (checked.length > 0) {
        bulkBar.style.display = 'flex';
        countSpan.innerText = checked.length + ' selected';
    } else {
        bulkBar.style.display = 'none';
        document.getElementById('select-all').checked = false;
    }
}

function bulkDelete() {
    if (!confirm('Are you sure you want to delete all selected products?')) return;
    const checked = Array.from(document.querySelectorAll('.product-checkbox:checked')).map(cb => cb.value);
    
    fetch('{{ route('admin.products.bulk-delete') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ ids: checked })
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) location.reload();
        else alert(data.message || 'Error occurred');
    });
}

function openSkuModal() {
    const checked = document.querySelectorAll('.product-checkbox:checked');
    const container = document.getElementById('sku-inputs-container');
    
    container.innerHTML = `
        <div style="margin-bottom: 1rem;">
            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;">This SKU will be applied to all ${checked.length} selected products.</p>
            <input type="text" id="bulk-sku-input" placeholder="Enter new SKU..." style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 6px;">
        </div>
    `;
    
    document.getElementById('skuModal').style.display = 'flex';
}

function closeSkuModal() {
    document.getElementById('skuModal').style.display = 'none';
}

function saveSkus() {
    const checked = Array.from(document.querySelectorAll('.product-checkbox:checked')).map(cb => cb.value);
    const newSku = document.getElementById('bulk-sku-input').value.trim();
    
    fetch('{{ route('admin.products.bulk-update-sku') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ ids: checked, sku: newSku })
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error updating SKUs');
        }
    })
    .catch(err => alert('An error occurred'));
}

function toggleStatus(id, element) {
    const isChecked = element.checked;
    fetch(`/admin/products/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ is_active: isChecked })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            element.checked = !isChecked;
            alert('Failed to update status.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        element.checked = !isChecked;
        alert('An error occurred. Please try again.');
    });
}

function toggleFeatured(id, element) {
    const isChecked = element.checked;
    fetch(`/admin/products/${id}/toggle-featured`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ is_featured: isChecked })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            element.checked = !isChecked;
            alert('Failed to update featured status.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        element.checked = !isChecked;
        alert('An error occurred. Please try again.');
    });
}
</script>
@endsection

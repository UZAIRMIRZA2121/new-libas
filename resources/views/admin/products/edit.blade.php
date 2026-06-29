@extends('layouts.admin')

@section('page_title', 'Edit Product')

@section('content')
<div class="table-card" style="max-width: 900px;">
    <div class="card-header">
        <h3>Edit Product</h3>
        <a href="{{ route('admin.products.index') }}" class="view-all-btn">Back to List</a>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" style="padding: 2rem;">
        @csrf
        @method('PUT')
        
        @if ($errors->any())
            <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #f87171;">
                <ul style="margin-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <!-- Left Column -->
            <div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">
                    @error('name')<span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>@enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Description</label>
                    <textarea name="description" rows="5" style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">{{ old('description', $product->description) }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Regular Price ($) *</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Old Price ($) (Optional)</label>
                        <input type="number" step="0.01" name="old_price" value="{{ old('old_price', $product->old_price) }}" style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Stock Quantity *</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Main Image</label>
                    @if($product->main_image_path)
                        <div style="margin-bottom: 1rem;">
                            <img src="{{ asset('storage/' . $product->main_image_path) }}" alt="Current Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" style="width: 100%; padding: 0.8rem; border: 1px dashed var(--border-color); border-radius: 8px; background: #f8fafc;">
                    <small style="color: var(--text-muted); display: block; margin-top: 0.3rem;">Leave empty to keep current image.</small>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Gallery Images</label>
                    @if($product->images->count() > 0)
                        <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem; flex-wrap: wrap;">
                        @foreach($product->images as $img)
                            <div style="position: relative; display: inline-block;" id="gallery-image-{{ $img->id }}">
                                <img src="{{ asset('storage/' . $img->image_path) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                <button type="button" onclick="deleteGalleryImage({{ $img->id }})" style="position: absolute; top: -5px; right: -5px; background: #ef4444; color: white; border: none; border-radius: 50%; width: 16px; height: 16px; font-size: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0; line-height: 1;">&times;</button>
                            </div>
                        @endforeach
                        </div>
                    @endif
                    <input type="file" name="gallery[]" accept="image/*" multiple style="width: 100%; padding: 0.8rem; border: 1px dashed var(--border-color); border-radius: 8px; background: #f8fafc;">
                    <small style="color: var(--text-muted); display: block; margin-top: 0.3rem;">Upload new images to add to the gallery.</small>
                </div>

                <script>
                    function deleteGalleryImage(id) {
                        if (confirm('Are you sure you want to delete this image?')) {
                            fetch(`{{ url('admin/product-images') }}/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('gallery-image-' + id).remove();
                                } else {
                                    alert('Error deleting image');
                                }
                            })
                            .catch(error => console.error('Error:', error));
                        }
                    }
                </script>

                <div style="margin-bottom: 1.5rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Product Colors</label>
                    <small style="color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Warning: Saving will replace all existing colors.</small>
                    <div id="colors-container">
                        @php $colorCount = max(3, $product->colors->count()); @endphp
                        @for($i=0; $i<$colorCount; $i++)
                            @php $existingColor = $product->colors->skip($i)->first(); @endphp
                            <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <input type="text" name="color_names[]" value="{{ $existingColor->name ?? '' }}" placeholder="Color Name" style="flex: 1; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 4px;">
                                <input type="color" name="color_hexes[]" value="{{ $existingColor->hex_code ?? '#000000' }}" style="width: 50px; height: 38px; border: 1px solid var(--border-color); border-radius: 4px; cursor: pointer;">
                            </div>
                        @endfor
                    </div>
                    <button type="button" onclick="addColorRow()" style="margin-top: 0.5rem; background: #e2e8f0; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">+ Add More Colors</button>
                </div>

                <div style="margin-bottom: 1.5rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Product Specifications (Optional)</label>
                    <small style="color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Warning: Saving will replace all existing specifications.</small>
                    <div id="specifications-container">
                        @php $specCount = max(4, $product->specifications->count()); @endphp
                        @for($i=0; $i<$specCount; $i++)
                            @php $existingSpec = $product->specifications->skip($i)->first(); @endphp
                            <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <input type="text" name="spec_keys[]" value="{{ $existingSpec->key ?? '' }}" placeholder="Key (e.g. SHIRT)" style="flex: 1; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 4px;">
                                <input type="text" name="spec_values[]" value="{{ $existingSpec->value ?? '' }}" placeholder="Value (e.g. Printed Lawn Shirt)" style="flex: 2; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 4px;">
                            </div>
                        @endfor
                    </div>
                    <button type="button" onclick="addSpecRow()" style="margin-top: 0.5rem; background: #e2e8f0; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">+ Add More Specs</button>
                </div>

                <script>
                    function addSpecRow() {
                        const container = document.getElementById('specifications-container');
                        const row = document.createElement('div');
                        row.style.display = 'flex';
                        row.style.gap = '0.5rem';
                        row.style.marginBottom = '0.5rem';
                        row.innerHTML = `
                            <input type="text" name="spec_keys[]" placeholder="Key" style="flex: 1; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 4px;">
                            <input type="text" name="spec_values[]" placeholder="Value" style="flex: 2; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 4px;">
                        `;
                        container.appendChild(row);
                    }

                    function addColorRow() {
                        const container = document.getElementById('colors-container');
                        const row = document.createElement('div');
                        row.style.display = 'flex';
                        row.style.gap = '0.5rem';
                        row.style.marginBottom = '0.5rem';
                        row.innerHTML = `
                            <input type="text" name="color_names[]" placeholder="Color Name" style="flex: 1; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 4px;">
                            <input type="color" name="color_hexes[]" value="#000000" style="width: 50px; height: 38px; border: 1px solid var(--border-color); border-radius: 4px; cursor: pointer;">
                        `;
                        container.appendChild(row);
                    }
                </script>

                <div style="margin-bottom: 1.5rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Fake Stats</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 0.5rem;">
                        <div>
                            <label style="font-size: 0.85rem; color: var(--text-muted);">Rating (0-5)</label>
                            <input type="number" step="0.1" name="rating" value="{{ old('rating', $product->rating) }}" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 4px;">
                        </div>
                        <div>
                            <label style="font-size: 0.85rem; color: var(--text-muted);">Review Count</label>
                            <input type="number" name="review_count" value="{{ old('review_count', $product->review_count) }}" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 4px;">
                        </div>
                    </div>
                    <div>
                        <label style="font-size: 0.85rem; color: var(--text-muted);">Bought Count</label>
                        <input type="number" name="bought_count" value="{{ old('bought_count', $product->bought_count) }}" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 4px;">
                    </div>
                </div>

                @php
                    $isSub = $product->category && $product->category->parent_id;
                    $mainCatId = $isSub ? $product->category->parent_id : ($product->category_id ?? '');
                    $subCatId = $isSub ? $product->category_id : '';
                @endphp
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Main Category</label>
                    <select id="main_category" onchange="loadSubCategories()" style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">
                        <option value="">Select Main Category</option>
                        @foreach($categories->whereNull('parent_id') as $category)
                            <option value="{{ $category->id }}" {{ $mainCatId == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Sub Category (Optional)</label>
                    <select id="sub_category" onchange="updateFinalCategory()" style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">
                        <option value="">Select Sub Category</option>
                    </select>
                </div>
                
                <input type="hidden" name="category_id" id="final_category_id" value="{{ $product->category_id }}">
                
                <script>
                    const allCategories = @json($categories);
                    const initialSubCatId = "{{ $subCatId }}";
                    
                    function loadSubCategories() {
                        const mainId = document.getElementById('main_category').value;
                        const subSelect = document.getElementById('sub_category');
                        subSelect.innerHTML = '<option value="">Select Sub Category</option>';
                        
                        updateFinalCategory();

                        if (mainId) {
                            const subs = allCategories.filter(c => c.parent_id == mainId);
                            subs.forEach(sub => {
                                const opt = document.createElement('option');
                                opt.value = sub.id;
                                opt.textContent = sub.name;
                                if (sub.id == initialSubCatId) {
                                    opt.selected = true;
                                }
                                subSelect.appendChild(opt);
                            });
                        }
                    }

                    function updateFinalCategory() {
                        const subId = document.getElementById('sub_category').value;
                        const mainId = document.getElementById('main_category').value;
                        document.getElementById('final_category_id').value = subId ? subId : mainId;
                    }

                    // Load initial subcategories on page load
                    window.onload = function() {
                        if (document.getElementById('main_category').value) {
                            loadSubCategories();
                            if (initialSubCatId) {
                                document.getElementById('sub_category').value = initialSubCatId;
                            }
                            updateFinalCategory();
                        }
                    };
                </script>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Brand</label>
                    <select name="brand_id" style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} style="width: 18px; height: 18px;">
                    <label for="is_active" style="font-weight: 500; cursor: pointer;">Product is Active</label>
                </div>

                <div style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} style="width: 18px; height: 18px;">
                    <label for="is_featured" style="font-weight: 500; cursor: pointer; color: #b45309;">Deal of the Day / Featured</label>
                </div>

                <button type="submit" style="width: 100%; background: var(--primary); color: white; border: none; padding: 1rem; border-radius: 8px; font-weight: 600; font-size: 1.1rem; cursor: pointer;">
                    Update Product
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

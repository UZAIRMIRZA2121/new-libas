@extends('layouts.admin')

@section('page_title', 'Add Product')

@section('content')
<div class="table-card" style="max-width: 900px;">
    <div class="card-header">
        <h3>Create New Product</h3>
        <a href="{{ route('admin.products.index') }}" class="view-all-btn">Back to List</a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" style="padding: 2rem;">
        @csrf
        
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
                    <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">
                    @error('name')<span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>@enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Description</label>
                    <textarea name="description" rows="5" style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">{{ old('description') }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Regular Price ($) *</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" required style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Old Price ($) (Optional)</label>
                        <input type="number" step="0.01" name="old_price" value="{{ old('old_price') }}" style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Stock Quantity *</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" required style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku') }}" style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Main Image</label>
                    <input type="file" name="image" accept="image/*" style="width: 100%; padding: 0.8rem; border: 1px dashed var(--border-color); border-radius: 8px; background: #f8fafc;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Gallery Images</label>
                    <input type="file" name="gallery[]" accept="image/*" multiple style="width: 100%; padding: 0.8rem; border: 1px dashed var(--border-color); border-radius: 8px; background: #f8fafc;">
                    <small style="color: var(--text-muted); display: block; margin-top: 0.3rem;">You can select multiple images.</small>
                </div>

                <div style="margin-bottom: 1.5rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Product Colors (Optional)</label>
                    @for($i=0; $i<3; $i++)
                        <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <input type="text" name="color_names[]" placeholder="Color Name (e.g. Pink)" style="flex: 1; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 4px;">
                            <input type="color" name="color_hexes[]" style="width: 50px; height: 38px; border: 1px solid var(--border-color); border-radius: 4px; cursor: pointer;">
                        </div>
                    @endfor
                </div>

                <div style="margin-bottom: 1.5rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Product Specifications (Optional)</label>
                    <small style="color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Add key-value pairs like "SHIRT" and "Printed Lawn Shirt 2.8 M".</small>
                    <div id="specifications-container">
                        @for($i=0; $i<4; $i++)
                            <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <input type="text" name="spec_keys[]" placeholder="Key (e.g. SHIRT)" style="flex: 1; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 4px;">
                                <input type="text" name="spec_values[]" placeholder="Value (e.g. Printed Lawn Shirt)" style="flex: 2; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 4px;">
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
                </script>

                <div style="margin-bottom: 1.5rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Fake Stats</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 0.5rem;">
                        <div>
                            <label style="font-size: 0.85rem; color: var(--text-muted);">Rating (0-5)</label>
                            <input type="number" step="0.1" name="rating" value="4.8" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 4px;">
                        </div>
                        <div>
                            <label style="font-size: 0.85rem; color: var(--text-muted);">Review Count</label>
                            <input type="number" name="review_count" value="120" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 4px;">
                        </div>
                    </div>
                    <div>
                        <label style="font-size: 0.85rem; color: var(--text-muted);">Bought Count</label>
                        <input type="number" name="bought_count" value="100" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 4px;">
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Main Category</label>
                    <select id="main_category" onchange="loadSubCategories()" style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">
                        <option value="">Select Main Category</option>
                        @foreach($categories->whereNull('parent_id') as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Sub Category (Optional)</label>
                    <select id="sub_category" onchange="updateFinalCategory()" style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">
                        <option value="">Select Sub Category</option>
                    </select>
                </div>
                
                <input type="hidden" name="category_id" id="final_category_id" value="">
                
                <script>
                    const allCategories = @json($categories);
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
                                subSelect.appendChild(opt);
                            });
                        }
                    }

                    function updateFinalCategory() {
                        const subId = document.getElementById('sub_category').value;
                        const mainId = document.getElementById('main_category').value;
                        document.getElementById('final_category_id').value = subId ? subId : mainId;
                    }
                </script>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Brand</label>
                    <select name="brand_id" style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-color); border-radius: 8px;">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked style="width: 18px; height: 18px;">
                    <label for="is_active" style="font-weight: 500; cursor: pointer;">Product is Active</label>
                </div>

                <div style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" style="width: 18px; height: 18px;">
                    <label for="is_featured" style="font-weight: 500; cursor: pointer; color: #b45309;">Deal of the Day / Featured</label>
                </div>

                <button type="submit" style="width: 100%; background: var(--primary); color: white; border: none; padding: 1rem; border-radius: 8px; font-weight: 600; font-size: 1.1rem; cursor: pointer;">
                    Save Product
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

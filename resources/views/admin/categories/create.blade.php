@extends('layouts.admin')

@section('page_title', 'Add Category')

@section('content')
<div class="table-card" style="max-width: 600px;">
    <div class="card-header">
        <h3>Create New Category</h3>
        <a href="{{ route('admin.categories.index') }}" class="view-all-btn">Back</a>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" style="padding: 1.5rem;">
        @csrf
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Category Name</label>
            <input type="text" name="name" required placeholder="e.g. Men" value="{{ old('name') }}" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; outline: none;">
            @error('name') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Parent Category (Optional)</label>
            <select name="parent_id" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; outline: none; background: white;">
                <option value="">None (Top Level)</option>
                @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                @endforeach
            </select>
            @error('parent_id') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Category Image</label>
            <input type="file" name="image" accept="image/*" style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; outline: none; background: #f8fafc;">
            @error('image') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <input type="checkbox" name="is_active" id="is_active" checked style="width: 16px; height: 16px;">
            <label for="is_active" style="font-size: 0.875rem; font-weight: 500;">Active (Display on storefront)</label>
        </div>

        <button type="submit" style="background: var(--primary); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; font-weight: 500; cursor: pointer;">Save Category</button>
    </form>
</div>
@endsection

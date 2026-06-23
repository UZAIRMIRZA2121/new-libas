@extends('layouts.admin')

@section('page_title', 'Add Banner')

@section('content')
<div class="table-card" style="max-width: 800px;">
    <div class="card-header">
        <h3>Create New Banner</h3>
        <a href="{{ route('admin.banners.index') }}" class="view-all-btn">Back</a>
    </div>

    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" style="padding: 1.5rem;">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Subtitle</label>
                <input type="text" name="subtitle" placeholder="e.g. Welcome to New Libas" value="{{ old('subtitle') }}" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; outline: none;">
                @error('subtitle') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Title</label>
                <input type="text" name="title" placeholder="e.g. Premium Collection" value="{{ old('title') }}" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; outline: none;">
                @error('title') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Description</label>
            <textarea name="description" rows="3" placeholder="e.g. Discover styles that define you." style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; outline: none;">{{ old('description') }}</textarea>
            @error('description') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Button Text</label>
                <input type="text" name="button_text" placeholder="e.g. Shop Now" value="{{ old('button_text') }}" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; outline: none;">
                @error('button_text') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Button Link</label>
                <input type="text" name="button_link" placeholder="e.g. /product" value="{{ old('button_link') }}" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; outline: none;">
                @error('button_link') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Banner Background Image</label>
            <input type="file" name="image" accept="image/*" style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; outline: none; background: #f8fafc;">
            @error('image') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <input type="checkbox" name="is_active" id="is_active" checked style="width: 16px; height: 16px;">
            <label for="is_active" style="font-size: 0.875rem; font-weight: 500;">Active (Display on storefront slider)</label>
        </div>

        <button type="submit" style="background: var(--primary); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; font-weight: 500; cursor: pointer;">Save Banner</button>
    </form>
</div>
@endsection

@extends('layouts.admin')

@section('page_title', 'Add Brand')

@section('content')
<div class="table-card" style="max-width: 600px;">
    <div class="card-header">
        <h3>Create New Brand</h3>
        <a href="{{ route('admin.brands.index') }}" class="view-all-btn">Back</a>
    </div>

    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" style="padding: 1.5rem;">
        @csrf
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Brand Name</label>
            <input type="text" name="name" required placeholder="e.g. Nike" value="{{ old('name') }}" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; outline: none;">
            @error('name') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Brand Logo</label>
            <input type="file" name="logo" accept="image/*" style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; outline: none; background: #f8fafc;">
            @error('logo') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Brand Banner</label>
            <input type="file" name="banner" accept="image/*" style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px; outline: none; background: #f8fafc;">
            <p style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">Recommended size: 1200x300 pixels</p>
            @error('banner') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <input type="checkbox" name="is_active" id="is_active" checked style="width: 16px; height: 16px;">
            <label for="is_active" style="font-size: 0.875rem; font-weight: 500;">Active (Display on storefront)</label>
        </div>

        <button type="submit" style="background: var(--primary); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; font-weight: 500; cursor: pointer;">Save Brand</button>
    </form>
</div>
@endsection

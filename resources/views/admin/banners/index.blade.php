@extends('layouts.admin')

@section('page_title', 'Banners')

@section('content')
<div class="table-card">
    <div class="card-header">
        <h3>All Banners</h3>
        <a href="{{ route('admin.banners.create') }}" class="view-all-btn" style="background: var(--primary); color: white; border-color: var(--primary);">+ Add Banner</a>
    </div>

    @if(session('success'))
        <div style="padding: 1rem 1.5rem; background: #f0fdf4; color: #166534; border-bottom: 1px solid #dcfce3;">
            {{ session('success') }}
        </div>
    @endif

    <table class="data-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Subtitle</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($banners as $banner)
                <tr>
                    <td>
                        @if($banner->image_path)
                            <img src="{{ Storage::url($banner->image_path) }}" alt="Banner Image" style="width: 100px; height: 50px; object-fit: cover; border-radius: 6px;">
                        @else
                            <div style="width: 100px; height: 50px; background: #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #94a3b8;"><i class="fas fa-image"></i></div>
                        @endif
                    </td>
                    <td><strong>{{ $banner->title ?? 'N/A' }}</strong></td>
                    <td>{{ $banner->subtitle ?? 'N/A' }}</td>
                    <td>
                        @if($banner->is_active)
                            <x-admin.badge colorClass="badge-green">Active</x-admin.badge>
                        @else
                            <x-admin.badge colorClass="badge-yellow">Inactive</x-admin.badge>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.banners.edit', $banner) }}" style="color: #3b82f6;"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this banner?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer;"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 2rem;">No banners found. Click "Add Banner" to create one.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

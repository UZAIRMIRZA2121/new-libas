@extends('layouts.admin')

@section('page_title', 'Categories')

@section('content')
<div class="table-card">
    <div class="card-header">
        <h3>All Categories</h3>
        <a href="{{ route('admin.categories.create') }}" class="view-all-btn" style="background: var(--primary); color: white; border-color: var(--primary);">+ Add Category</a>
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
                <th>Name</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr style="cursor: pointer; background: #fff;" onclick="toggleSubcategories({{ $category->id }})">
                    <td>
                        @if($category->image_path)
                            <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                        @else
                            <div style="width: 50px; height: 50px; background: #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #94a3b8;"><i class="fas fa-image"></i></div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $category->name }}</strong>
                        @if($category->children->count() > 0)
                            <br><small style="color: #64748b;"><i class="fas fa-chevron-down"></i> {{ $category->children->count() }} Subcategories</small>
                        @endif
                    </td>
                    <td>{{ $category->slug }}</td>
                    <td>
                        @if($category->is_active)
                            <x-admin.badge colorClass="badge-green">Active</x-admin.badge>
                        @else
                            <x-admin.badge colorClass="badge-yellow">Inactive</x-admin.badge>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;" onclick="event.stopPropagation()">
                            <a href="{{ route('admin.categories.edit', $category) }}" style="color: #3b82f6;"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer;"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @foreach($category->children as $child)
                <tr class="subcategory-{{ $category->id }}" style="display: none; background: #f8fafc;">
                    <td style="padding-left: 2rem;">
                        @if($child->image_path)
                            <img src="{{ Storage::url($child->image_path) }}" alt="{{ $child->name }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">
                        @else
                            <div style="width: 40px; height: 40px; background: #cbd5e1; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #64748b;"><i class="fas fa-image"></i></div>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-level-up-alt" style="transform: rotate(90deg); color: #94a3b8;"></i>
                            {{ $child->name }}
                        </div>
                    </td>
                    <td>{{ $child->slug }}</td>
                    <td>
                        @if($child->is_active)
                            <x-admin.badge colorClass="badge-green">Active</x-admin.badge>
                        @else
                            <x-admin.badge colorClass="badge-yellow">Inactive</x-admin.badge>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.categories.edit', $child) }}" style="color: #3b82f6;"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.categories.destroy', $child) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subcategory?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer;"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 2rem;">No categories found. Click "Add Category" to create one.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function toggleSubcategories(id) {
    const rows = document.querySelectorAll('.subcategory-' + id);
    rows.forEach(row => {
        row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
    });
}
</script>
@endsection

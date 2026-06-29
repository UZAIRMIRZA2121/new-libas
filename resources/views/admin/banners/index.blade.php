@extends('layouts.admin')

@section('page_title', 'Banners')

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
                        <label class="switch" title="Toggle Active Status">
                            <input type="checkbox" onchange="toggleStatus({{ $banner->id }}, this)" {{ $banner->is_active ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
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

<script>
function toggleStatus(id, element) {
    const isChecked = element.checked;
    
    fetch(`/admin/banners/${id}/toggle-status`, {
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
</script>
@endsection

@extends('layouts.admin')

@section('page_title', 'Edit Coupon')

@section('content')
<div class="table-card" style="max-width: 600px;">
    <div class="card-header">
        <h3>Edit Coupon</h3>
        <a href="{{ route('admin.coupons.index') }}" class="view-all-btn">Back</a>
    </div>

    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" style="padding: 1.5rem;">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Assign To User</label>
            <select name="email" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; outline: none;">
                <option value="">Everyone (General Coupon)</option>
                @foreach($users as $user)
                    <option value="{{ $user->email }}" {{ old('email', $coupon->email) == $user->email ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            @error('email') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Coupon Code</label>
            <input type="text" name="code" required value="{{ old('code', $coupon->code) }}" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; outline: none; text-transform: uppercase;">
            @error('code') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem;">Discount Percentage (%)</label>
            <input type="number" name="discount_percentage" required min="1" max="100" value="{{ old('discount_percentage', $coupon->discount_percentage) }}" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; outline: none;">
            @error('discount_percentage') <span style="color: #ef4444; font-size: 0.75rem;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <input type="checkbox" name="is_used" id="is_used" {{ $coupon->is_used ? 'checked' : '' }} style="width: 16px; height: 16px;">
            <label for="is_used" style="font-size: 0.875rem; font-weight: 500;">Mark as Used</label>
        </div>

        <button type="submit" style="background: var(--primary); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; font-weight: 500; cursor: pointer;">Update Coupon</button>
    </form>
</div>
@endsection

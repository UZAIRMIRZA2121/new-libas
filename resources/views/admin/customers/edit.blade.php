@extends('layouts.admin')

@section('page_title', 'Edit Customer')

@section('content')
<div class="dashboard-grid">
    <div class="table-card" style="grid-column: span 12; padding: 2rem;">
        <div class="card-header" style="margin-bottom: 2rem; border-bottom: none; padding: 0;">
            <h3>Edit Customer: {{ $customer->name }}</h3>
        </div>

        @if(session('success'))
            <div style="padding: 1rem; background: #f0fdf4; color: #166534; border-radius: 8px; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        <style>
            .form-input {
                width: 100%;
                padding: 0.75rem 1rem;
                border: 1px solid #cbd5e1;
                border-radius: 8px;
                background-color: #f8fafc;
                color: #334155;
                font-size: 0.95rem;
                transition: all 0.2s;
                outline: none;
            }
            .form-input:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
                background-color: #ffffff;
            }
        </style>

        <form action="{{ route('admin.customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" required class="form-input">
                    @error('name') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" required class="form-input">
                    @error('email') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="form-input">
                    @error('phone') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">City</label>
                    <input type="text" name="city" value="{{ old('city', $customer->city) }}" class="form-input">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Address</label>
                <input type="text" name="address" value="{{ old('address', $customer->address) }}" class="form-input">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Apartment</label>
                    <input type="text" name="apartment" value="{{ old('apartment', $customer->apartment) }}" class="form-input">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Postal Code</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $customer->postal_code) }}" class="form-input">
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" style="background: var(--primary); color: white; border: none; padding: 0.75rem 2rem; border-radius: 8px; font-weight: 600; cursor: pointer;">Update Customer</button>
                <a href="{{ route('admin.customers.index') }}" style="background: #e2e8f0; color: var(--text-main); text-decoration: none; padding: 0.75rem 2rem; border-radius: 8px; font-weight: 600;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

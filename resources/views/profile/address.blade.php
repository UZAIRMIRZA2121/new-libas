@extends('layouts.customer')
@section('customer_title', 'Shipping Address')

@section('customer_content')
<style>
    .address-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    @media (max-width: 768px) {
        .address-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@if(session('success'))
    <div style="background: #dcfce7; color: #16a34a; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('profile.address.update') }}" method="POST">
    @csrf
    @method('patch')

    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; font-weight: 600; color: #334155; margin-bottom: 0.5rem; font-size: 0.9rem;">Address</label>
        <input type="text" name="address" value="{{ old('address', $user->address) }}" placeholder="Street address, P.O. box, etc." required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; color: #475569; font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
    </div>

    <div style="margin-bottom: 1.5rem;">
        <label style="display: block; font-weight: 600; color: #334155; margin-bottom: 0.5rem; font-size: 0.9rem;">Apartment, suite, etc. (optional)</label>
        <input type="text" name="apartment" value="{{ old('apartment', $user->apartment) }}" placeholder="Apartment, suite, unit, building, floor, etc." style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; color: #475569; font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
    </div>

    <div class="address-grid">
        <div>
            <label style="display: block; font-weight: 600; color: #334155; margin-bottom: 0.5rem; font-size: 0.9rem;">City</label>
            <input type="text" name="city" value="{{ old('city', $user->city) }}" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; color: #475569; font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
        </div>
        <div>
            <label style="display: block; font-weight: 600; color: #334155; margin-bottom: 0.5rem; font-size: 0.9rem;">Postal Code</label>
            <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; color: #475569; font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
        </div>
    </div>

    <div style="text-align: right;">
        <button type="submit" style="background: #ff5722; color: white; border: none; padding: 0.75rem 2.5rem; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: background 0.2s;">
            Save Address
        </button>
    </div>
</form>
@endsection

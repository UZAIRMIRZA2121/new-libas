@extends('layouts.customer')
@section('customer_title', '')

@section('customer_content')
<style>
    .profile-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    @media (max-width: 768px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@if(session('success'))
    <div style="background: #dcfce7; color: #16a34a; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background: #fef2f2; color: #ef4444; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('patch')
    
    <div style="text-align: center; margin-bottom: 3rem;">
        <div style="position: relative; display: inline-block;">
            <div style="width: 120px; height: 120px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden; margin: 0 auto;">
                @if($user->avatar)
                    <img id="avatar_preview" src="{{ asset('storage/' . $user->avatar) }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i id="avatar_icon" class="fas fa-user" style="font-size: 4rem; color: #cbd5e1;"></i>
                    <img id="avatar_preview" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                @endif
            </div>
            
            <label for="avatar_upload" style="position: absolute; bottom: 0; right: 0; background: #ff5722; color: white; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <i class="fas fa-camera" style="font-size: 0.9rem;"></i>
            </label>
            <input type="file" id="avatar_upload" name="avatar" style="display: none;" accept="image/*">
        </div>
        
        <h3 style="margin: 1rem 0 0 0; font-size: 1.25rem; font-weight: 700;">{{ $user->name }}</h3>
    </div>

    @php
        $nameParts = explode(' ', $user->name, 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';
    @endphp

    <div class="profile-grid">
        <div>
            <label style="display: block; font-weight: 600; color: #334155; margin-bottom: 0.5rem; font-size: 0.9rem;">First Name</label>
            <input type="text" name="first_name" value="{{ old('first_name', $firstName) }}" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; color: #475569; font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
        </div>
        <div>
            <label style="display: block; font-weight: 600; color: #334155; margin-bottom: 0.5rem; font-size: 0.9rem;">Last Name</label>
            <input type="text" name="last_name" value="{{ old('last_name', $lastName) }}" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; color: #475569; font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
        </div>
    </div>

    <div class="profile-grid">
        <div>
            <label style="display: block; font-weight: 600; color: #334155; margin-bottom: 0.5rem; font-size: 0.9rem;">Phone Number</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; color: #475569; font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
        </div>
        <div>
            <label style="display: block; font-weight: 600; color: #334155; margin-bottom: 0.5rem; font-size: 0.9rem;">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; color: #475569; font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
        </div>
    </div>

    <div class="profile-grid" style="margin-bottom: 2rem;">
        <div style="position: relative;">
            <label style="display: block; font-weight: 600; color: #334155; margin-bottom: 0.5rem; font-size: 0.9rem;">New Password</label>
            <input type="password" name="password" placeholder="Minimum 8 characters long" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; color: #475569; font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
            <i class="fas fa-eye-slash" style="position: absolute; right: 1rem; top: 2.5rem; color: #94a3b8; cursor: pointer;"></i>
        </div>
        <div style="position: relative;">
            <label style="display: block; font-weight: 600; color: #334155; margin-bottom: 0.5rem; font-size: 0.9rem;">Confirm Password</label>
            <input type="password" name="password_confirmation" placeholder="Minimum 8 characters long" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 8px; color: #475569; font-size: 0.95rem; outline: none; transition: border-color 0.2s;">
            <i class="fas fa-eye-slash" style="position: absolute; right: 1rem; top: 2.5rem; color: #94a3b8; cursor: pointer;"></i>
        </div>
    </div>

    <div style="text-align: right;">
        <button type="submit" style="background: #ff5722; color: white; border: none; padding: 0.75rem 2.5rem; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: background 0.2s;">
            Update
        </button>
    </div>
</form>

<script>
    document.getElementById('avatar_upload').addEventListener('change', function(e) {
        if(this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById('avatar_preview');
                let icon = document.getElementById('avatar_icon');
                if (icon) icon.style.display = 'none';
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endsection

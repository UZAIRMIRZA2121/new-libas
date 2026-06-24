@extends('layouts.frontend')

@section('title', 'Register - New Libas')

@section('content')
<style>
    .auth-container {
        max-width: 450px;
        margin: 4rem auto;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 2.5rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .auth-header h2 {
        font-size: 1.8rem;
        color: var(--text-main);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .auth-header p {
        color: var(--text-muted);
        font-size: 0.95rem;
    }
    .form-group {
        margin-bottom: 1.2rem;
    }
    .form-label {
        display: block;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        color: #475569;
        font-size: 0.95rem;
        outline: none;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
    }
    .btn-auth {
        width: 100%;
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.8rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-auth:hover {
        background: var(--primary-hover);
    }
    .auth-footer {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.95rem;
        color: var(--text-muted);
    }
    .auth-footer a {
        color: var(--primary-color);
        font-weight: 600;
    }
</style>

<div class="auth-container" style="max-width: 550px;">
    <div class="auth-header">
        <h2>Create an Account</h2>
        <p>Join New Libas to start shopping</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus class="form-control" placeholder="John Doe">
            @error('name')
                <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-control" placeholder="you@example.com">
            @error('email')
                <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group" style="display: flex; gap: 1rem;">
            <div style="flex: 1;">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" required class="form-control" placeholder="••••••••">
                @error('password')
                    <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
                @enderror
            </div>
            <div style="flex: 1;">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="form-control" placeholder="••••••••">
                @error('password_confirmation')
                    <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn-auth" style="margin-top: 1.5rem;">Create Account</button>
        
        <div style="text-align: center; margin: 1.5rem 0; position: relative;">
            <div style="position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: #e2e8f0; z-index: 1;"></div>
            <span style="position: relative; z-index: 2; background: white; padding: 0 10px; color: #64748b; font-size: 0.9rem;">or</span>
        </div>

        <a href="{{ route('google.login') }}" style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 0.8rem; border: 1px solid #e2e8f0; border-radius: 8px; color: #334155; text-decoration: none; font-weight: 600; transition: background 0.2s;">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" style="width: 20px; height: 20px;">
            Sign up with Google
        </a>
    </form>

    <div class="auth-footer" style="margin-top: 2rem;">
        Already have an account? <a href="{{ route('login') }}">Log in here</a>
    </div>
</div>
@endsection

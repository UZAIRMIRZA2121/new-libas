@extends('layouts.frontend')

@section('title', 'Login - New Libas')

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
    .auth-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }
    .remember-me {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }
    .forgot-link {
        color: var(--primary-color);
        font-weight: 500;
        transition: color 0.2s;
    }
    .forgot-link:hover {
        color: var(--primary-hover);
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

<div class="auth-container">
    <div class="auth-header">
        <h2>Welcome Back</h2>
        <p>Sign in to your account to continue</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div style="background: #dcfce7; color: #16a34a; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="form-control" placeholder="you@example.com">
            @error('email')
                <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" required class="form-control" placeholder="••••••••">
            @error('password')
                <div style="color: var(--danger); font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-actions">
            <label for="remember_me" class="remember-me">
                <input type="checkbox" id="remember_me" name="remember" style="accent-color: var(--primary-color);">
                <span>Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="forgot-link" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            @endif
        </div>

        <button type="submit" class="btn-auth">Log in</button>
    </form>

    <div class="auth-footer">
        Don't have an account? <a href="{{ route('register') }}">Sign up here</a>
    </div>
</div>
@endsection

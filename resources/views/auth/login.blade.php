@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="auth-pro-page">
    <div class="auth-pro-card auth-login-card">
        <div class="auth-pro-head">
            <a href="{{ route('home') }}" class="auth-pro-logo"><i class="bi bi-house-heart"></i></a>
            <span class="auth-pro-kicker">KosKu Account</span>
            <h1>Masuk ke KosKu</h1>
            <p>Kelola kos, pantau booking, atau temukan kamar terbaik dengan akun Anda.</p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="auth-pro-form">
            @csrf
            <div class="auth-field">
                <label>Email</label>
                <div class="auth-input-wrap">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                </div>
                @error('email')<small class="auth-error">{{ $message }}</small>@enderror
            </div>

            <div class="auth-field">
                <label>Password</label>
                <div class="auth-input-wrap">
                    <i class="bi bi-lock"></i>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
            </div>

            <div class="auth-meta-row">
                <label class="auth-check"><input type="checkbox" name="remember"> <span>Ingat saya</span></label>
                <a href="{{ route('password.request') }}">Lupa password?</a>
            </div>

            <button class="btn btn-primary auth-pro-btn"><i class="bi bi-box-arrow-in-right me-1"></i> Login</button>
        </form>

        <div class="auth-switch-text">
            Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')
<div class="auth-pro-page">
    <div class="auth-pro-card auth-login-card">
        <div class="auth-pro-head">
            <a href="{{ route('home') }}" class="auth-pro-logo"><i class="bi bi-house-heart"></i></a>
            <span class="auth-pro-kicker">KosKu Account</span>
            <h1>Reset Password</h1>
            <p>Masukkan password baru untuk akun Anda.</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="auth-pro-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="auth-field">
                <label>Email</label>
                <div class="auth-input-wrap">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" value="{{ old('email', $email) }}" placeholder="nama@email.com" required>
                </div>
                @error('email')<small class="auth-error">{{ $message }}</small>@enderror
            </div>

            <div class="auth-field">
                <label>Password Baru</label>
                <div class="auth-input-wrap">
                    <i class="bi bi-lock"></i>
                    <input type="password" name="password" placeholder="Minimal 8 karakter" required>
                </div>
                @error('password')<small class="auth-error">{{ $message }}</small>@enderror
            </div>

            <div class="auth-field">
                <label>Konfirmasi Password</label>
                <div class="auth-input-wrap">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru" required>
                </div>
            </div>

            <button class="btn btn-primary auth-pro-btn"><i class="bi bi-shield-check me-1"></i> Reset Password</button>
        </form>
    </div>
</div>
@endsection

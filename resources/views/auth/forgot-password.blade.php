@extends('layouts.app')
@section('title', 'Lupa Password')
@section('content')
<div class="auth-pro-page">
    <div class="auth-pro-card auth-login-card">
        <div class="auth-pro-head">
            <a href="{{ route('home') }}" class="auth-pro-logo"><i class="bi bi-house-heart"></i></a>
            <span class="auth-pro-kicker">KosKu Account</span>
            <h1>Lupa Password</h1>
            <p>Masukkan email akun Anda, kami akan kirimkan link untuk mereset password.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="auth-pro-form">
            @csrf
            <div class="auth-field">
                <label>Email</label>
                <div class="auth-input-wrap">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                </div>
                @error('email')<small class="auth-error">{{ $message }}</small>@enderror
            </div>

            <button class="btn btn-primary auth-pro-btn"><i class="bi bi-send me-1"></i> Kirim Link Reset</button>
        </form>

        <div class="auth-switch-text">
            Ingat password? <a href="{{ route('login') }}">Login sekarang</a>
        </div>
    </div>
</div>
@endsection

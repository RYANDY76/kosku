@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="auth-pro-page">
    <div class="auth-pro-card auth-register-card">
        <div class="auth-pro-head">
            <a href="{{ route('home') }}" class="auth-pro-logo"><i class="bi bi-person-plus"></i></a>
            <span class="auth-pro-kicker">KosKu Account</span>
            <h1>Daftar Akun KosKu</h1>
            <p>Pilih peran akun sesuai kebutuhan: pencari kos atau pemilik kos.</p>
        </div>

        <form method="POST" action="{{ route('register.store') }}" class="auth-pro-form">
            @csrf
            <div class="auth-field">
                <label>Nama Lengkap</label>
                <div class="auth-input-wrap">
                    <i class="bi bi-person"></i>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap" required>
                </div>
                @error('name')<small class="auth-error">{{ $message }}</small>@enderror
            </div>

            <div class="auth-field">
                <label>Email</label>
                <div class="auth-input-wrap">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                </div>
                @error('email')<small class="auth-error">{{ $message }}</small>@enderror
            </div>

            <div class="auth-field">
                <label>Daftar sebagai</label>
                <div class="role-choice-grid">
                    <label class="role-choice-card">
                        <input type="radio" name="role" value="user" @checked(old('role', 'user') === 'user')>
                        <span><i class="bi bi-search-heart"></i><strong>Pencari Kos</strong><small>Cari kos dan ajukan booking kamar.</small></span>
                    </label>
                    <label class="role-choice-card">
                        <input type="radio" name="role" value="pemilik" @checked(old('role') === 'pemilik')>
                        <span><i class="bi bi-building"></i><strong>Pemilik Kos</strong><small>Kelola kos, kamar, booking, dan pembayaran.</small></span>
                    </label>
                </div>
            </div>

            <div class="auth-two-col">
                <div class="auth-field">
                    <label>Password</label>
                    <div class="auth-input-wrap">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="password" placeholder="Minimal 8 karakter" required>
                    </div>
                    @error('password')<small class="auth-error">{{ $message }}</small>@enderror
                </div>
                <div class="auth-field">
                    <label>Konfirmasi Password</label>
                    <div class="auth-input-wrap">
                        <i class="bi bi-shield-lock"></i>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password" required>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary auth-pro-btn"><i class="bi bi-person-check me-1"></i> Register</button>
        </form>

        <div class="auth-switch-text">
            Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
        </div>
    </div>
</div>
@endsection

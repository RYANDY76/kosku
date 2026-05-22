@extends(auth()->user()->role === 'user' ? 'layouts.app' : 'layouts.dashboard')
@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')
@section('content')
@php
    $roleLabel = match($user->role) { 'admin' => 'Administrator', 'pemilik' => 'Pemilik Kos', default => 'Pencari Kos' };
    $roleIcon = match($user->role) { 'admin' => 'bi-shield-check', 'pemilik' => 'bi-building', default => 'bi-person' };
@endphp
<section class="profile-page-shell {{ $user->role === 'user' ? 'container py-5' : '' }}">
    <div class="profile-cover-card">
        <div class="profile-cover-main">
            <div class="profile-big-avatar"><i class="bi {{ $roleIcon }}"></i></div>
            <div>
                <span class="section-kicker">AKUN KOSKU</span>
                <h2>{{ $user->name }}</h2>
                <p>{{ $user->email }}</p>
                <span class="profile-role-badge">{{ $roleLabel }}</span>
            </div>
        </div>
        <a href="{{ route('profile.edit') }}" class="btn btn-primary"><i class="bi bi-pencil-square me-1"></i>Edit Profil</a>
    </div>

    <div class="profile-info-grid">
        <div class="profile-info-card">
            <i class="bi bi-person-badge"></i>
            <span>Nama</span>
            <strong>{{ $user->name }}</strong>
        </div>
        <div class="profile-info-card">
            <i class="bi bi-envelope"></i>
            <span>Email</span>
            <strong>{{ $user->email }}</strong>
        </div>
        <div class="profile-info-card">
            <i class="bi bi-award"></i>
            <span>Role</span>
            <strong>{{ $roleLabel }}</strong>
        </div>
        <div class="profile-info-card">
            <i class="bi bi-calendar-check"></i>
            <span>Bergabung</span>
            <strong>{{ optional($user->created_at)->format('d M Y') }}</strong>
        </div>
    </div>

    @if($user->role === 'pemilik')
        <div class="profile-detail-card mt-4">
            <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                <div><span class="section-kicker">PROFIL PEMILIK</span><h3>Informasi Kontak Pemilik</h3></div>
                <span class="profile-role-badge soft">Untuk calon penyewa</span>
            </div>
            <div class="row g-3">
                <div class="col-md-6"><div class="profile-row"><span>No WhatsApp</span><strong>{{ $user->profile->no_wa ?? 'Belum diisi' }}</strong></div></div>
                <div class="col-md-6"><div class="profile-row"><span>Alamat Domisili</span><strong>{{ $user->profile->alamat ?? 'Belum diisi' }}</strong></div></div>
            </div>
        </div>
    @endif
</section>
@endsection

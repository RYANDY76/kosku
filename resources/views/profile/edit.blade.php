@extends(auth()->user()->role === 'user' ? 'layouts.app' : 'layouts.dashboard')
@section('title', 'Edit Profil')
@section('page_title', 'Edit Profil')
@section('content')
<section class="profile-page-shell {{ auth()->user()->role === 'user' ? 'container py-5' : '' }}">
    <div class="profile-detail-card">
        <div class="d-flex align-items-center justify-content-between gap-3 mb-4">
            <div><span class="section-kicker">AKUN KOSKU</span><h2 class="fw-black mb-1">Edit Profil</h2><p class="text-muted mb-0">Perbarui data akun agar informasi pengguna tetap akurat.</p></div>
            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
        <form method="POST" action="{{ route('profile.update') }}" class="row g-3">
            @csrf @method('PATCH')
            <div class="col-md-6"><label class="form-label">Nama</label><input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required></div>
            @if($user->role === 'pemilik')
                <div class="col-md-6"><label class="form-label">Nomor WhatsApp</label><input type="text" name="no_wa" value="{{ old('no_wa', $user->profile->no_wa ?? '') }}" class="form-control" placeholder="6281234567890"></div>
                <div class="col-md-6"><label class="form-label">Alamat Domisili</label><input type="text" name="alamat" value="{{ old('alamat', $user->profile->alamat ?? '') }}" class="form-control" placeholder="Alamat domisili"></div>
            @endif
            <div class="col-12 d-flex gap-2 justify-content-end mt-4"><a href="{{ route('profile.show') }}" class="btn btn-light">Kembali</a><button class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Profil</button></div>
        </form>
    </div>
</section>
@endsection

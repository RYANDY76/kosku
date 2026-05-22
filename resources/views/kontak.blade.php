@extends('layouts.app')
@section('title', 'Kontak')
@section('content')
<section class="container py-5">
    <div class="content-card p-4 p-lg-5">
        <span class="section-kicker">KONTAK KOSKU</span>
        <h1 class="fw-black mb-2">Butuh bantuan mencari atau mengelola kos?</h1>
        <p class="text-muted mb-4">Hubungi admin KosKu untuk bantuan booking, verifikasi kos, atau pembayaran.</p>
        <div class="row g-3">
            <div class="col-md-4"><div class="profile-info-card h-100"><i class="bi bi-whatsapp"></i><span>WhatsApp</span><strong>6281234567890</strong></div></div>
            <div class="col-md-4"><div class="profile-info-card h-100"><i class="bi bi-envelope"></i><span>Email</span><strong>admin@kosku.test</strong></div></div>
            <div class="col-md-4"><div class="profile-info-card h-100"><i class="bi bi-geo-alt"></i><span>Area</span><strong>Palu dan sekitarnya</strong></div></div>
        </div>
    </div>
</section>
@endsection

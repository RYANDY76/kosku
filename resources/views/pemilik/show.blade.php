@extends('layouts.app')
@section('title', 'Profil Pemilik - ' . $pemilik->name)
@section('content')
<section class="owner-profile-hero">
    <div class="container">
        <a href="{{ route('kos.index') }}" class="btn btn-light mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>
        <div class="owner-profile-card">
            <div class="owner-avatar-lg">{{ strtoupper(substr($pemilik->name, 0, 1)) }}</div>
            <div>
                <span class="section-kicker">PEMILIK TERVERIFIKASI</span>
                <h1>{{ $pemilik->name }}</h1>
                <p>{{ $pemilik->profile->alamat ?? 'Alamat pemilik belum dilengkapi.' }}</p>
                <div class="owner-profile-meta">
                    <span><i class="bi bi-house-door"></i> {{ $pemilik->kos->count() }} kos aktif</span>
                    <span><i class="bi bi-star-fill"></i> {{ number_format($pemilik->kos->avg('reviews_avg_rating') ?: 0, 1) }} rating rata-rata</span>
                    <span><i class="bi bi-whatsapp"></i> {{ $pemilik->profile->no_wa ?? 'Nomor belum ada' }}</span>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="container py-5">
    <div class="section-heading">
        <div><span>DAFTAR KOS</span><h2>Kos Milik {{ $pemilik->name }}</h2></div>
        @if($pemilik->profile?->no_wa)
            <a target="_blank" class="btn btn-success" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pemilik->profile->no_wa) }}"><i class="bi bi-whatsapp"></i> Hubungi Pemilik</a>
        @endif
    </div>
    <div class="row g-4">
        @forelse($pemilik->kos as $kos)
            <div class="col-md-6 col-xl-4">@include('components.kos-card', ['kos' => $kos, 'variant' => 'grid'])</div>
        @empty
            <div class="col-12"><div class="empty-mini"><i class="bi bi-house"></i><strong>Belum ada kos aktif</strong><span>Kos pemilik akan tampil setelah diverifikasi admin.</span></div></div>
        @endforelse
    </div>
</div>
@endsection

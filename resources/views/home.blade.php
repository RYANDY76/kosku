@extends('layouts.app')
@section('title', 'Home')
@section('content')
<section class="landing-room-hero" id="home">
    <div class="landing-room-overlay"></div>
    <div class="landing-room-content premium-hero-content">
        <span>KOS TERPERCAYA DI PALU</span>
        <h1>Temukan kos yang tepat.</h1>
        <p>Cari kos berdasarkan area, budget, tipe kamar, dan fasilitas. Informasi harga, foto, lokasi, dan ketersediaan kamar ditampilkan dengan jelas.</p>

        <form action="{{ route('kos.index') }}" class="hero-search-card">
            <div class="hero-search-field">
                <i class="bi bi-search"></i>
                <input name="q" placeholder="Cari nama kos atau lokasi">
            </div>
            <select name="area" class="hero-search-select">
                <option value="">Semua area</option>
                @foreach($areas as $area)
                    <option value="{{ $area }}">{{ $area }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary"><i class="bi bi-search me-1"></i> Cari Kos</button>
        </form>

        <div class="hero-trust-grid">
            <div><strong>{{ $totalKos }}</strong><span>Kos terverifikasi</span></div>
            <div><strong>{{ $totalKamarTersedia }}</strong><span>Kamar tersedia</span></div>
            <div><strong>{{ $areaCount }}</strong><span>Area di Palu</span></div>
        </div>
    </div>
</section>

<section class="landing-benefits" id="tentang">
    <div class="container landing-container">
        <div class="benefit-card-clean">
            <i class="bi bi-shield-check"></i>
            <h3>Data Terverifikasi</h3>
            <p>Informasi kos dikelola oleh pemilik dan dapat diverifikasi oleh admin sebelum tampil di website.</p>
        </div>
        <div class="benefit-card-clean">
            <i class="bi bi-door-open"></i>
            <h3>Detail Kamar Jelas</h3>
            <p>Foto, harga, fasilitas, status kamar, dan kontak pemilik tersedia sebelum pengajuan sewa.</p>
        </div>
        <div class="benefit-card-clean">
            <i class="bi bi-geo-alt"></i>
            <h3>Lokasi Mudah Dicek</h3>
            <p>Setiap detail kos dapat dilengkapi alamat dan maps agar pencari kos lebih yakin sebelum booking.</p>
        </div>
    </div>
</section>

<section class="landing-recommendations">
    <div class="container landing-container">
        <div class="section-heading compact-heading">
            <div>
                <span>REKOMENDASI</span>
                <h2>Rekomendasi Kos</h2>
            </div>
            <a href="{{ route('kos.index') }}" class="btn btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="row g-4">
            @forelse($premiumKos->take(3) as $kos)
                <div class="col-md-4">@include('components.kos-card', ['kos' => $kos, 'variant' => 'grid'])</div>
            @empty
                @foreach($latestKos->take(3) as $kos)
                    <div class="col-md-4">@include('components.kos-card', ['kos' => $kos, 'variant' => 'grid'])</div>
                @endforeach
            @endforelse
        </div>
    </div>
</section>
@endsection

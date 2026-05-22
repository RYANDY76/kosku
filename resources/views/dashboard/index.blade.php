@extends(auth()->user()->role === 'user' ? 'layouts.app' : 'layouts.dashboard')
@section('title', 'Dashboard')
@section('page_title', auth()->user()->role === 'admin' ? 'Admin Panel' : 'Dashboard ' . ucfirst(auth()->user()->role))
@section('content')
@if(auth()->user()->role === 'user')
<section class="user-market-hero">
    <div class="container">
        <div class="user-market-card">
            <div class="user-market-copy">
                <span class="section-kicker">PENCARI KOS</span>
                <h1>Temukan kos yang sesuai.</h1>
                <p>Mulai dari pencarian kos, cek detail lokasi, lalu ajukan sewa. Riwayat booking dan pembayaran tetap tersedia dari menu akun.</p>
            </div>
            <form action="{{ route('kos.index') }}" class="user-market-search">
                <div class="user-search-input">
                    <i class="bi bi-search"></i>
                    <input name="q" placeholder="Cari kos atau lokasi">
                </div>
                <button class="btn btn-primary"><i class="bi bi-search me-1"></i> Cari Kos</button>
            </form>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="user-quick-links mb-4" aria-label="Menu akun pencari kos">
        <a href="{{ route('user.bookings') }}"><i class="bi bi-calendar2-check"></i><span>Booking Saya</span><strong>{{ $bookingSaya->whereIn('status', ['pending', 'approved'])->count() }} aktif</strong></a>
        <a href="{{ route('user.payments') }}"><i class="bi bi-receipt"></i><span>Pembayaran Saya</span><strong>{{ $paymentsSaya->whereIn('status', ['unpaid', 'pending'])->count() }} menunggu</strong></a>
        <a href="{{ route('profile.show') }}"><i class="bi bi-person-circle"></i><span>Profil</span><strong>Kelola akun</strong></a>
    </div>

    <div class="section-heading compact-heading">
        <div><span>REKOMENDASI</span><h2>Rekomendasi Kos</h2></div>
        <a href="{{ route('kos.index') }}" class="btn btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="row g-4">
        @forelse($rekomendasi->take(6) as $item)
            <div class="col-md-6 col-xl-4">@include('components.kos-card', ['kos'=>$item, 'variant'=>'grid'])</div>
        @empty
            <div class="col-12"><div class="empty-state"><i class="bi bi-house-heart"></i><h4>Belum ada rekomendasi</h4><p>Data kos akan tampil setelah pemilik menambahkan kos yang tersedia.</p></div></div>
        @endforelse
    </div>
</div>
@else
@php
    $isAdminPanel = auth()->user()->role === 'admin';
    $pageRoute = $isAdminPanel ? 'admin.page' : 'pemilik.page';
    $panelLink = fn($section, $page) => route($pageRoute, ['section' => $section, 'page' => $page]);
    $availableUnits = $kamarTersedia ?? 0;
    $pendingPaymentTotal = ($paymentPending ?? 0) + ($paymentUnpaid ?? 0);
    $mainCards = [
        ['title'=>'Total Kos', 'value'=>$totalKos ?? 0, 'suffix'=>'kos terdaftar', 'icon'=>'bi-houses', 'tone'=>'blue', 'url'=>$panelLink('master-data','properti')],
        ['title'=>'Kamar Tersedia', 'value'=>$availableUnits, 'suffix'=>'siap disewa', 'icon'=>'bi-door-open', 'tone'=>'green', 'url'=>$panelLink('master-data','properti')],
        ['title'=>'Booking Menunggu', 'value'=>$bookingPending ?? 0, 'suffix'=>'menunggu konfirmasi', 'icon'=>'bi-calendar2-check', 'tone'=>'orange', 'url'=>$panelLink('transaksi','kontrak-sewa')],
        ['title'=>'Pembayaran Menunggu', 'value'=>$pendingPaymentTotal, 'suffix'=>'tagihan perlu dicek', 'icon'=>'bi-credit-card', 'tone'=>'purple', 'url'=>$panelLink('transaksi','pembayaran')],
    ];
@endphp

<section class="pro-dashboard-head">
    <div>
        <span>{{ $isAdminPanel ? 'ADMIN KOSKU' : 'PEMILIK KOS' }}</span>
        <h2>{{ $isAdminPanel ? 'Dashboard Admin' : 'Dashboard Pemilik Kos' }}</h2>
        <p>{{ $isAdminPanel ? 'Pantau kos, pengguna, booking, dan pembayaran dari satu panel.' : 'Kelola kos, kamar, booking masuk, dan pembayaran penyewa.' }}</p>
    </div>
    <div class="pro-dashboard-actions">
        @if(! $isAdminPanel)
            <a href="{{ route('dashboard.kelola-kos.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Kos</a>
        @endif
        <a href="{{ route('kos.index') }}" class="btn btn-outline-primary"><i class="bi bi-window-stack"></i> Lihat Website</a>
    </div>
</section>

<div class="pro-stat-grid">
    @foreach($mainCards as $card)
        <a href="{{ $card['url'] }}" class="pro-stat-card {{ $card['tone'] }}">
            <span class="pro-stat-icon"><i class="bi {{ $card['icon'] }}"></i></span>
            <div><small>{{ $card['title'] }}</small><strong>{{ $card['value'] }}</strong><em>{{ $card['suffix'] }}</em></div>
        </a>
    @endforeach
</div>

<div class="pro-dashboard-grid single-column">
    <section class="pro-panel">
        <div class="pro-panel-head"><div><span>TERBARU</span><h3>Booking Masuk</h3></div><a href="{{ $panelLink('transaksi','kontrak-sewa') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a></div>
        <div class="table-responsive">
            <table class="table pro-table align-middle mb-0">
                <thead><tr><th>Pemesan</th><th>Kos</th><th>Kamar</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @forelse($bookings->take(5) as $booking)
                    <tr>
                        <td><strong>{{ $booking->nama_pemesan }}</strong><br><small>{{ $booking->user->email ?? '-' }}</small></td>
                        <td>{{ $booking->kos->nama_kos ?? '-' }}</td>
                        <td>{{ $booking->kamar->kode_kamar ?? $booking->kamar->tipe_kamar ?? '-' }}</td>
                        <td><span class="pro-status-badge {{ $booking->status }}">{{ $booking->status_label }}</span></td>
                        <td class="text-end"><a href="{{ $panelLink('transaksi','kontrak-sewa') }}" class="btn btn-sm btn-light">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="pro-empty-state"><i class="bi bi-calendar2-check"></i><strong>Belum ada booking masuk</strong><span>Pengajuan sewa terbaru akan tampil di sini.</span></div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

@endif
@endsection


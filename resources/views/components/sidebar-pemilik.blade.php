<aside class="sidebar owner-sidebar easy-sidebar minimal-role-sidebar super-simple-sidebar pro-sidebar">
    @php
        $ownerLink = fn($section, $page) => route('pemilik.page', ['section' => $section, 'page' => $page]);
        $activeOwner = fn($section, $page) => request()->routeIs('pemilik.page') && request()->route('section') === $section && request()->route('page') === $page ? 'active' : '';
    @endphp
    <div class="sidebar-brand pro-sidebar-brand"><i class="bi bi-house-heart"></i><span>KosKu</span></div>
    <div class="sidebar-profile compact-profile pro-sidebar-profile">
        <div class="avatar"><i class="bi bi-building"></i></div>
        <div><strong>{{ auth()->user()->name }}</strong><small>Pemilik Kos</small></div>
    </div>
    <nav class="sidebar-menu pro-sidebar-menu">
        <a class="{{ request()->routeIs('pemilik.dashboard') || request()->routeIs('dashboard.index') ? 'active' : '' }}" href="{{ route('pemilik.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a class="{{ $activeOwner('master-data','properti') }}" href="{{ $ownerLink('master-data','properti') }}"><i class="bi bi-houses"></i> Kos Saya</a>
        <a class="{{ $activeOwner('transaksi','kontrak-sewa') }}" href="{{ $ownerLink('transaksi','kontrak-sewa') }}"><i class="bi bi-calendar2-check"></i> Booking</a>
        <a class="{{ $activeOwner('transaksi','pembayaran') }}" href="{{ $ownerLink('transaksi','pembayaran') }}"><i class="bi bi-credit-card"></i> Pembayaran</a>
        <a href="{{ route('kos.index') }}"><i class="bi bi-search-heart"></i> Website</a>
    </nav>
</aside>

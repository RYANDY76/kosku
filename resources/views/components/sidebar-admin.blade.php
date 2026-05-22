<aside class="sidebar dark-sidebar admin-complex-sidebar minimal-role-sidebar super-simple-sidebar pro-sidebar">
    @php
        $adminLink = fn($section, $page) => route('admin.page', ['section' => $section, 'page' => $page]);
        $activePage = fn($section, $page) => request()->routeIs('admin.page') && request()->route('section') === $section && request()->route('page') === $page ? 'active' : '';
    @endphp
    <div class="sidebar-brand pro-sidebar-brand">
        <i class="bi bi-house-heart-fill"></i>
        <span>KosKu</span>
    </div>

    <div class="sidebar-profile admin-profile-card compact-profile pro-sidebar-profile">
        <div class="avatar"><i class="bi bi-shield-check"></i></div>
        <div><strong>{{ auth()->user()->name }}</strong><small>Administrator</small></div>
    </div>

    <nav class="sidebar-menu admin-menu pro-sidebar-menu">
        <a class="{{ request()->routeIs('admin.dashboard') || request()->routeIs('dashboard.index') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a class="{{ $activePage('master-data','properti') }}" href="{{ $adminLink('master-data','properti') }}"><i class="bi bi-houses"></i> Data Kos</a>
        <a class="{{ $activePage('transaksi','kontrak-sewa') }}" href="{{ $adminLink('transaksi','kontrak-sewa') }}"><i class="bi bi-calendar2-check"></i> Booking</a>
        <a class="{{ $activePage('transaksi','pembayaran') }}" href="{{ $adminLink('transaksi','pembayaran') }}"><i class="bi bi-credit-card"></i> Pembayaran</a>
        <a class="{{ $activePage('master-data','penyewa') }}" href="{{ $adminLink('master-data','penyewa') }}"><i class="bi bi-people"></i> Pengguna</a>
        <a href="{{ route('kos.index') }}"><i class="bi bi-window-stack"></i> Website</a>
    </nav>
</aside>

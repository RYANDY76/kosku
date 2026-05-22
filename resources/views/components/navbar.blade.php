<nav class="navbar navbar-expand-lg public-navbar simple-header sticky-top">
    <div class="container">
        <a class="navbar-brand simple-brand" href="{{ route('home') }}">KosKu</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3 mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('kos.index') }}">Cari Kos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('kontak') }}">Kontak</a></li>
                @auth
                    @php
                        $roleLabel = match(auth()->user()->role) {
                            'admin' => 'Admin',
                            'pemilik' => 'Pemilik Kos',
                            default => 'Pencari Kos',
                        };
                        $roleIcon = match(auth()->user()->role) {
                            'admin' => 'bi-shield-check',
                            'pemilik' => 'bi-building',
                            default => 'bi-person',
                        };
                    @endphp
                    <li class="nav-item dropdown profile-nav-item">
                        <button class="profile-nav-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="profile-avatar"><i class="bi {{ $roleIcon }}"></i></span>
                            <span class="profile-nav-text"><strong>{{ \Illuminate\Support\Str::limit(auth()->user()->name, 16) }}</strong><small>{{ $roleLabel }}</small></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown shadow-sm">
                            <div class="profile-dropdown-head">
                                <div class="profile-avatar lg"><i class="bi {{ $roleIcon }}"></i></div>
                                <div><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->email }}</small><span>{{ $roleLabel }}</span></div>
                            </div>
                            <a class="dropdown-item" href="{{ route('profile.show') }}"><i class="bi bi-person-circle me-2"></i>Profil Saya</a>
                            @if(auth()->user()->role === 'user')
                                <a class="dropdown-item" href="{{ route('dashboard.index') }}"><i class="bi bi-house-heart me-2"></i>Beranda Saya</a>
                                <a class="dropdown-item" href="{{ route('user.bookings') }}"><i class="bi bi-calendar2-check me-2"></i>Booking Saya</a>
                                <a class="dropdown-item" href="{{ route('user.payments') }}"><i class="bi bi-receipt me-2"></i>Pembayaran Saya</a>
                            @else
                                <a class="dropdown-item" href="{{ route('dashboard.index') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">@csrf<button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button></form>
                        </div>
                    </li>
                @else
                    <li class="nav-item"><a href="{{ route('login') }}" class="btn btn-primary btn-sm px-3">Login / Daftar</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<nav class="navbar navbar-expand-lg public-navbar simple-header sticky-top">
    <div class="container">
        <a class="navbar-brand simple-brand" href="<?php echo e(route('home')); ?>">KosKu</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3 mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?php echo e(route('home')); ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo e(route('kos.index')); ?>">Cari Kos</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo e(route('kontak')); ?>">Kontak</a></li>
                <?php if(auth()->guard()->check()): ?>
                    <?php
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
                    ?>
                    <li class="nav-item dropdown profile-nav-item">
                        <button class="profile-nav-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="profile-avatar"><i class="bi <?php echo e($roleIcon); ?>"></i></span>
                            <span class="profile-nav-text"><strong><?php echo e(\Illuminate\Support\Str::limit(auth()->user()->name, 16)); ?></strong><small><?php echo e($roleLabel); ?></small></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown shadow-sm">
                            <div class="profile-dropdown-head">
                                <div class="profile-avatar lg"><i class="bi <?php echo e($roleIcon); ?>"></i></div>
                                <div><strong><?php echo e(auth()->user()->name); ?></strong><small><?php echo e(auth()->user()->email); ?></small><span><?php echo e($roleLabel); ?></span></div>
                            </div>
                            <a class="dropdown-item" href="<?php echo e(route('profile.show')); ?>"><i class="bi bi-person-circle me-2"></i>Profil Saya</a>
                            <?php if(auth()->user()->role === 'user'): ?>
                                <a class="dropdown-item" href="<?php echo e(route('dashboard.index')); ?>"><i class="bi bi-house-heart me-2"></i>Beranda Saya</a>
                                <a class="dropdown-item" href="<?php echo e(route('user.bookings')); ?>"><i class="bi bi-calendar2-check me-2"></i>Booking Saya</a>
                                <a class="dropdown-item" href="<?php echo e(route('user.payments')); ?>"><i class="bi bi-receipt me-2"></i>Pembayaran Saya</a>
                            <?php else: ?>
                                <a class="dropdown-item" href="<?php echo e(route('dashboard.index')); ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?><button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button></form>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a href="<?php echo e(route('login')); ?>" class="btn btn-primary btn-sm px-3">Login / Daftar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<?php /**PATH C:\xampp\htdocs\KosKu_UAS_FINAL_UIUX_PRO\resources\views/components/navbar.blade.php ENDPATH**/ ?>
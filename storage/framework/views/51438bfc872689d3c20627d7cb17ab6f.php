<aside class="sidebar owner-sidebar easy-sidebar minimal-role-sidebar super-simple-sidebar pro-sidebar">
    <?php
        $ownerLink = fn($section, $page) => route('pemilik.page', ['section' => $section, 'page' => $page]);
        $activeOwner = fn($section, $page) => request()->routeIs('pemilik.page') && request()->route('section') === $section && request()->route('page') === $page ? 'active' : '';
    ?>
    <div class="sidebar-brand pro-sidebar-brand"><i class="bi bi-house-heart"></i><span>KosKu</span></div>
    <div class="sidebar-profile compact-profile pro-sidebar-profile">
        <div class="avatar"><i class="bi bi-building"></i></div>
        <div><strong><?php echo e(auth()->user()->name); ?></strong><small>Pemilik Kos</small></div>
    </div>
    <nav class="sidebar-menu pro-sidebar-menu">
        <a class="<?php echo e(request()->routeIs('pemilik.dashboard') || request()->routeIs('dashboard.index') ? 'active' : ''); ?>" href="<?php echo e(route('pemilik.dashboard')); ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a class="<?php echo e($activeOwner('master-data','properti')); ?>" href="<?php echo e($ownerLink('master-data','properti')); ?>"><i class="bi bi-houses"></i> Kos Saya</a>
        <a class="<?php echo e($activeOwner('transaksi','kontrak-sewa')); ?>" href="<?php echo e($ownerLink('transaksi','kontrak-sewa')); ?>"><i class="bi bi-calendar2-check"></i> Booking</a>
        <a class="<?php echo e($activeOwner('transaksi','pembayaran')); ?>" href="<?php echo e($ownerLink('transaksi','pembayaran')); ?>"><i class="bi bi-credit-card"></i> Pembayaran</a>
        <a href="<?php echo e(route('kos.index')); ?>"><i class="bi bi-search-heart"></i> Website</a>
    </nav>
</aside>
<?php /**PATH C:\xampp\htdocs\KosKu_UAS_FINAL_UIUX_PRO\resources\views/components/sidebar-pemilik.blade.php ENDPATH**/ ?>
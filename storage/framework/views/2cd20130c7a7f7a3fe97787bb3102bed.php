<aside class="sidebar dark-sidebar admin-complex-sidebar minimal-role-sidebar super-simple-sidebar pro-sidebar">
    <?php
        $adminLink = fn($section, $page) => route('admin.page', ['section' => $section, 'page' => $page]);
        $activePage = fn($section, $page) => request()->routeIs('admin.page') && request()->route('section') === $section && request()->route('page') === $page ? 'active' : '';
    ?>
    <div class="sidebar-brand pro-sidebar-brand">
        <i class="bi bi-house-heart-fill"></i>
        <span>KosKu</span>
    </div>

    <div class="sidebar-profile admin-profile-card compact-profile pro-sidebar-profile">
        <div class="avatar"><i class="bi bi-shield-check"></i></div>
        <div><strong><?php echo e(auth()->user()->name); ?></strong><small>Administrator</small></div>
    </div>

    <nav class="sidebar-menu admin-menu pro-sidebar-menu">
        <a class="<?php echo e(request()->routeIs('admin.dashboard') || request()->routeIs('dashboard.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.dashboard')); ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a class="<?php echo e($activePage('master-data','properti')); ?>" href="<?php echo e($adminLink('master-data','properti')); ?>"><i class="bi bi-houses"></i> Data Kos</a>
        <a class="<?php echo e($activePage('transaksi','kontrak-sewa')); ?>" href="<?php echo e($adminLink('transaksi','kontrak-sewa')); ?>"><i class="bi bi-calendar2-check"></i> Booking</a>
        <a class="<?php echo e($activePage('transaksi','pembayaran')); ?>" href="<?php echo e($adminLink('transaksi','pembayaran')); ?>"><i class="bi bi-credit-card"></i> Pembayaran</a>
        <a class="<?php echo e($activePage('master-data','penyewa')); ?>" href="<?php echo e($adminLink('master-data','penyewa')); ?>"><i class="bi bi-people"></i> Pengguna</a>
        <a href="<?php echo e(route('kos.index')); ?>"><i class="bi bi-window-stack"></i> Website</a>
    </nav>
</aside>
<?php /**PATH C:\xampp\htdocs\KosKu_UAS_FINAL_UIUX_PRO\resources\views/components/sidebar-admin.blade.php ENDPATH**/ ?>
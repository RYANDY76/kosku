<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page_title', auth()->user()->role === 'admin' ? 'Admin Panel' : 'Dashboard ' . ucfirst(auth()->user()->role)); ?>
<?php $__env->startSection('content'); ?>
<?php if(auth()->user()->role === 'user'): ?>
<section class="user-market-hero">
    <div class="container">
        <div class="user-market-card">
            <div class="user-market-copy">
                <span class="section-kicker">PENCARI KOS</span>
                <h1>Temukan kos yang sesuai.</h1>
                <p>Mulai dari pencarian kos, cek detail lokasi, lalu ajukan sewa. Riwayat booking dan pembayaran tetap tersedia dari menu akun.</p>
            </div>
            <form action="<?php echo e(route('kos.index')); ?>" class="user-market-search">
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
        <a href="<?php echo e(route('user.bookings')); ?>"><i class="bi bi-calendar2-check"></i><span>Booking Saya</span><strong><?php echo e($bookingSaya->whereIn('status', ['pending', 'approved'])->count()); ?> aktif</strong></a>
        <a href="<?php echo e(route('user.payments')); ?>"><i class="bi bi-receipt"></i><span>Pembayaran Saya</span><strong><?php echo e($paymentsSaya->whereIn('status', ['unpaid', 'pending'])->count()); ?> menunggu</strong></a>
        <a href="<?php echo e(route('profile.show')); ?>"><i class="bi bi-person-circle"></i><span>Profil</span><strong>Kelola akun</strong></a>
    </div>

    <div class="section-heading compact-heading">
        <div><span>REKOMENDASI</span><h2>Rekomendasi Kos</h2></div>
        <a href="<?php echo e(route('kos.index')); ?>" class="btn btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $rekomendasi->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-6 col-xl-4"><?php echo $__env->make('components.kos-card', ['kos'=>$item, 'variant'=>'grid'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12"><div class="empty-state"><i class="bi bi-house-heart"></i><h4>Belum ada rekomendasi</h4><p>Data kos akan tampil setelah pemilik menambahkan kos yang tersedia.</p></div></div>
        <?php endif; ?>
    </div>
</div>
<?php else: ?>
<?php
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
?>

<section class="pro-dashboard-head">
    <div>
        <span><?php echo e($isAdminPanel ? 'ADMIN KOSKU' : 'PEMILIK KOS'); ?></span>
        <h2><?php echo e($isAdminPanel ? 'Dashboard Admin' : 'Dashboard Pemilik Kos'); ?></h2>
        <p><?php echo e($isAdminPanel ? 'Pantau kos, pengguna, booking, dan pembayaran dari satu panel.' : 'Kelola kos, kamar, booking masuk, dan pembayaran penyewa.'); ?></p>
    </div>
    <div class="pro-dashboard-actions">
        <?php if(! $isAdminPanel): ?>
            <a href="<?php echo e(route('dashboard.kelola-kos.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Kos</a>
        <?php endif; ?>
        <a href="<?php echo e(route('kos.index')); ?>" class="btn btn-outline-primary"><i class="bi bi-window-stack"></i> Lihat Website</a>
    </div>
</section>

<div class="pro-stat-grid">
    <?php $__currentLoopData = $mainCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e($card['url']); ?>" class="pro-stat-card <?php echo e($card['tone']); ?>">
            <span class="pro-stat-icon"><i class="bi <?php echo e($card['icon']); ?>"></i></span>
            <div><small><?php echo e($card['title']); ?></small><strong><?php echo e($card['value']); ?></strong><em><?php echo e($card['suffix']); ?></em></div>
        </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="pro-dashboard-grid single-column">
    <section class="pro-panel">
        <div class="pro-panel-head"><div><span>TERBARU</span><h3>Booking Masuk</h3></div><a href="<?php echo e($panelLink('transaksi','kontrak-sewa')); ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a></div>
        <div class="table-responsive">
            <table class="table pro-table align-middle mb-0">
                <thead><tr><th>Pemesan</th><th>Kos</th><th>Kamar</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $bookings->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong><?php echo e($booking->nama_pemesan); ?></strong><br><small><?php echo e($booking->user->email ?? '-'); ?></small></td>
                        <td><?php echo e($booking->kos->nama_kos ?? '-'); ?></td>
                        <td><?php echo e($booking->kamar->kode_kamar ?? $booking->kamar->tipe_kamar ?? '-'); ?></td>
                        <td><span class="pro-status-badge <?php echo e($booking->status); ?>"><?php echo e($booking->status_label); ?></span></td>
                        <td class="text-end"><a href="<?php echo e($panelLink('transaksi','kontrak-sewa')); ?>" class="btn btn-sm btn-light">Detail</a></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5"><div class="pro-empty-state"><i class="bi bi-calendar2-check"></i><strong>Belum ada booking masuk</strong><span>Pengajuan sewa terbaru akan tampil di sini.</span></div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php endif; ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make(auth()->user()->role === 'user' ? 'layouts.app' : 'layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KosKu_UAS_FINAL_UIUX_PRO\resources\views/dashboard/index.blade.php ENDPATH**/ ?>
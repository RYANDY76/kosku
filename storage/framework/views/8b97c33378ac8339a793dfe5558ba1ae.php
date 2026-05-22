<?php
    $titles = [
        'properti' => ['Kos & Kamar', 'Kelola data kos, kamar, foto, fasilitas, harga, dan status verifikasi.'],
        'penyewa' => ['Pengguna', 'Kelola akun pencari kos dan pantau aktivitas booking.'],
        'kontrak-sewa' => ['Booking', 'Pantau dan proses pengajuan sewa dari pencari kos.'],
        'pembayaran' => ['Pembayaran', 'Validasi bukti pembayaran dan status tagihan.'],
    ];
    [$pageTitle, $pageDescription] = $titles[$page] ?? ['Panel', 'Kelola data KosKu.'];
    $panelRole = $panelRole ?? auth()->user()->role;
    $isAdminPanel = $panelRole === 'admin';
    $pageRoute = $isAdminPanel ? 'admin.page' : 'pemilik.page';
    $dashboardRoute = $isAdminPanel ? 'admin.dashboard' : 'pemilik.dashboard';
?>
<?php $__env->startSection('title', $pageTitle); ?>
<?php $__env->startSection('page_title', $pageTitle); ?>
<?php $__env->startSection('content'); ?>
<section class="admin-page-head clean-management-head">
    <div>
        <span><?php echo e($isAdminPanel ? 'ADMIN' : 'PEMILIK KOS'); ?></span>
        <h2><?php echo e($pageTitle); ?></h2>
        <p><?php echo e($pageDescription); ?></p>
    </div>
    <div class="admin-page-actions">
        <a class="btn btn-light" href="<?php echo e(route($dashboardRoute)); ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <?php if($page === 'properti'): ?>
            <a class="btn btn-primary" href="<?php echo e(route('dashboard.kelola-kos.create')); ?>"><i class="bi bi-plus-circle"></i> Tambah Kos</a>
        <?php endif; ?>
    </div>
</section>

<?php if($page === 'properti'): ?>
    <div class="dashboard-card admin-page-card">
        <div class="card-header-clean">
            <div><span class="section-kicker">DATA KOS</span><h4>Daftar Kos</h4><p><?php echo e($isAdminPanel ? 'Admin dapat memverifikasi kos sebelum tampil di website.' : 'Pemilik hanya dapat mengelola kos miliknya sendiri.'); ?></p></div>
        </div>
        <div class="table-responsive">
            <table class="table table-admin mb-0 align-middle">
                <thead><tr><th>Foto</th><th>Nama Kos</th><th>Pemilik</th><th>Harga</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $kosSaya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><a href="<?php echo e(route('kos.show', $item)); ?>"><img class="table-cover-img" loading="lazy" src="<?php echo e($item->foto_url); ?>" alt="<?php echo e($item->nama_kos); ?>" onerror="this.src='<?php echo e(asset('images/default-kos.jpg')); ?>'"></a></td>
                        <td>
                            <a class="table-title" href="<?php echo e(route('kos.show', $item)); ?>"><?php echo e($item->nama_kos); ?></a><br>
                            <small><?php echo e($item->lokasi_area); ?> · <?php echo e(ucfirst($item->tipe_kos)); ?></small><br>
                            <span class="badge text-bg-<?php echo e($item->verification_status === 'approved' ? 'success' : ($item->verification_status === 'rejected' ? 'danger' : 'warning')); ?>"><?php echo e($item->verification_label); ?></span>
                        </td>
                        <td><?php echo e($item->pemilik->name ?? '-'); ?></td>
                        <td><strong><?php echo e($item->harga_rupiah); ?></strong></td>
                        <td><?php echo e($item->status_label); ?></td>
                        <td class="text-nowrap">
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('kos.show', $item)); ?>"><i class="bi bi-eye"></i></a>
                            <a class="btn btn-sm btn-warning" href="<?php echo e(route('dashboard.kelola-kos.edit', $item)); ?>"><i class="bi bi-pencil"></i></a>
                            <?php if($isAdminPanel): ?>
                                <form method="POST" action="<?php echo e(route('dashboard.kelola-kos.verify', $item)); ?>" class="mt-2 d-flex gap-1 admin-inline-form"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <select name="verification_status" class="form-select form-select-sm">
                                        <option value="pending" <?php if($item->verification_status==='pending'): echo 'selected'; endif; ?>>Pending</option>
                                        <option value="approved" <?php if($item->verification_status==='approved'): echo 'selected'; endif; ?>>Setujui</option>
                                        <option value="rejected" <?php if($item->verification_status==='rejected'): echo 'selected'; endif; ?>>Tolak</option>
                                    </select>
                                    <label class="premium-toggle"><input type="checkbox" name="premium" value="1" <?php if($item->premium): echo 'checked'; endif; ?>> Pilihan</label>
                                    <button class="btn btn-sm btn-primary" data-confirm="Simpan status kos ini?">Simpan</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data kos.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="dashboard-card admin-page-card mt-4">
        <div class="card-header-clean"><div><span class="section-kicker">KAMAR</span><h4>Data Kamar</h4><p>Kelola unit kamar, harga, dan status ketersediaan.</p></div></div>
        <div class="table-responsive">
            <table class="table table-admin mb-0 align-middle">
                <thead><tr><th>Kamar</th><th>Kos</th><th>Harga/Bulan</th><th>Stok</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $kosSaya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kosItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $__currentLoopData = $kosItem->kamar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kamar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($kamar->kode_kamar ?: $kamar->tipe_kamar); ?></strong><br><small><?php echo e($kamar->tipe_kamar); ?></small></td>
                            <td><?php echo e($kosItem->nama_kos); ?></td>
                            <td><strong><?php echo e($kamar->harga_rupiah); ?></strong></td>
                            <td><?php echo e($kamar->jumlah_kamar); ?> unit</td>
                            <td><span class="badge text-bg-<?php echo e($kamar->status === 'tersedia' ? 'success' : 'secondary'); ?>"><?php echo e(ucfirst($kamar->status)); ?></span></td>
                            <td><a class="btn btn-sm btn-warning" href="<?php echo e(route('dashboard.kelola-kos.kamar.edit', [$kosItem, $kamar])); ?>"><i class="bi bi-pencil"></i> Edit</a></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada kamar.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php if($page === 'penyewa' && $isAdminPanel): ?>
    <div class="dashboard-card admin-page-card">
        <div class="card-header-clean"><div><span class="section-kicker">PENGGUNA</span><h4>Data Pengguna</h4><p>Akun pencari kos yang terdaftar di sistem.</p></div></div>
        <div class="table-responsive"><table class="table table-admin mb-0"><thead><tr><th>Nama</th><th>Email</th><th>Booking</th><th>Pembayaran Valid</th><th>Bergabung</th></tr></thead><tbody>
        <?php $__empty_1 = true; $__currentLoopData = $penyewaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr><td><strong><?php echo e($u->name); ?></strong></td><td><?php echo e($u->email); ?></td><td><?php echo e($allBookings->where('user_id', $u->id)->count()); ?></td><td>Rp<?php echo e(number_format($allPayments->where('user_id', $u->id)->where('status','valid')->sum('nominal'),0,',','.')); ?></td><td><?php echo e(optional($u->created_at)->format('d M Y')); ?></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pengguna.</td></tr>
        <?php endif; ?>
        </tbody></table></div>
    </div>
<?php endif; ?>

<?php if($page === 'kontrak-sewa'): ?>
    <div class="dashboard-card admin-page-card">
        <div class="card-header-clean"><div><span class="section-kicker">BOOKING</span><h4>Pengajuan Sewa</h4><p>Terima atau tolak booking berdasarkan ketersediaan kamar.</p></div></div>
        <div class="table-responsive"><table class="table table-admin mb-0 align-middle"><thead><tr><th>Pemesan</th><th>Kos</th><th>Kamar</th><th>Tanggal Masuk</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
        <?php $__empty_1 = true; $__currentLoopData = $allBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><strong><?php echo e($booking->nama_pemesan); ?></strong><br><small><?php echo e($booking->user->email ?? '-'); ?></small></td>
                <td><?php echo e($booking->kos->nama_kos ?? '-'); ?></td>
                <td><?php echo e($booking->kamar->tipe_kamar ?? '-'); ?></td>
                <td><?php echo e(optional($booking->tanggal_masuk)->format('d M Y') ?: '-'); ?></td>
                <td><span class="badge text-bg-<?php echo e($booking->status_badge); ?>"><?php echo e($booking->status_label); ?></span></td>
                <td class="text-nowrap">
                    <a class="btn btn-sm btn-outline-primary me-1" href="<?php echo e(route('dashboard.bookings.show', $booking)); ?>"><i class="bi bi-eye"></i> Detail</a>
                    <form method="POST" action="<?php echo e(route('dashboard.bookings.update', $booking)); ?>" class="d-inline-flex gap-1 admin-inline-form"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <select name="status" class="form-select form-select-sm"><option value="pending" <?php if($booking->status==='pending'): echo 'selected'; endif; ?>>Menunggu</option><option value="approved" <?php if($booking->status==='approved'): echo 'selected'; endif; ?>>Terima</option><option value="rejected" <?php if($booking->status==='rejected'): echo 'selected'; endif; ?>>Tolak</option></select>
                        <button class="btn btn-sm btn-primary" data-confirm="Update status booking ini?">Update</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada booking.</td></tr>
        <?php endif; ?>
        </tbody></table></div>
    </div>
<?php endif; ?>

<?php if($page === 'pembayaran'): ?>
    <div class="dashboard-card admin-page-card">
        <div class="card-header-clean"><div><span class="section-kicker">PEMBAYARAN</span><h4>Validasi Pembayaran</h4><p>Periksa bukti transfer sebelum mengubah status pembayaran.</p></div></div>
        <div class="table-responsive"><table class="table table-admin mb-0 align-middle"><thead><tr><th>Penyewa</th><th>Kos</th><th>Nominal</th><th>Jatuh Tempo</th><th>Status</th><th>Bukti</th><th>Aksi</th></tr></thead><tbody>
        <?php $__empty_1 = true; $__currentLoopData = $allPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><strong><?php echo e($payment->user->name ?? '-'); ?></strong><br><small><?php echo e($payment->user->email ?? '-'); ?></small></td>
                <td><?php echo e($payment->kos->nama_kos ?? '-'); ?></td>
                <td><strong><?php echo e($payment->nominal_rupiah); ?></strong></td>
                <td><?php echo e(optional($payment->jatuh_tempo)->format('d M Y') ?: '-'); ?></td>
                <td><span class="badge text-bg-<?php echo e($payment->status_badge); ?>"><?php echo e($payment->status_label); ?></span></td>
                <td><?php if($payment->bukti): ?><a target="_blank" class="btn btn-sm btn-outline-primary" href="<?php echo e(asset('storage/' . $payment->bukti)); ?>">Lihat</a><?php else: ?><span class="text-muted">Belum ada</span><?php endif; ?></td>
                <td><form method="POST" action="<?php echo e(route('dashboard.payments.updateStatus', $payment)); ?>" class="d-flex gap-1 admin-inline-form"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><select name="status" class="form-select form-select-sm"><option value="unpaid" <?php if($payment->status==='unpaid'): echo 'selected'; endif; ?>>Belum Bayar</option><option value="pending" <?php if($payment->status==='pending'): echo 'selected'; endif; ?>>Menunggu</option><option value="valid" <?php if($payment->status==='valid'): echo 'selected'; endif; ?>>Valid</option><option value="rejected" <?php if($payment->status==='rejected'): echo 'selected'; endif; ?>>Tolak</option></select><input type="text" name="catatan" class="form-control form-control-sm" placeholder="Catatan" value="<?php echo e($payment->catatan); ?>"><button class="btn btn-sm btn-primary" data-confirm="Simpan status pembayaran ini?">Simpan</button></form></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pembayaran.</td></tr>
        <?php endif; ?>
        </tbody></table></div>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KosKu_UAS_FINAL_UIUX_PRO\resources\views/dashboard/admin-page.blade.php ENDPATH**/ ?>
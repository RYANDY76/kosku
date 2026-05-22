<?php $__env->startSection('title', 'Home'); ?>
<?php $__env->startSection('content'); ?>
<section class="landing-room-hero" id="home">
    <div class="landing-room-overlay"></div>
    <div class="landing-room-content premium-hero-content">
        <span>KOS TERPERCAYA DI PALU</span>
        <h1>Temukan kos yang tepat.</h1>
        <p>Cari kos berdasarkan area, budget, tipe kamar, dan fasilitas. Informasi harga, foto, lokasi, dan ketersediaan kamar ditampilkan dengan jelas.</p>

        <form action="<?php echo e(route('kos.index')); ?>" class="hero-search-card">
            <div class="hero-search-field">
                <i class="bi bi-search"></i>
                <input name="q" placeholder="Cari nama kos atau lokasi">
            </div>
            <select name="area" class="hero-search-select">
                <option value="">Semua area</option>
                <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($area); ?>"><?php echo e($area); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button class="btn btn-primary"><i class="bi bi-search me-1"></i> Cari Kos</button>
        </form>

        <div class="hero-trust-grid">
            <div><strong><?php echo e($totalKos); ?></strong><span>Kos terverifikasi</span></div>
            <div><strong><?php echo e($totalKamarTersedia); ?></strong><span>Kamar tersedia</span></div>
            <div><strong><?php echo e($areaCount); ?></strong><span>Area di Palu</span></div>
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
            <a href="<?php echo e(route('kos.index')); ?>" class="btn btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="row g-4">
            <?php $__empty_1 = true; $__currentLoopData = $premiumKos->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-md-4"><?php echo $__env->make('components.kos-card', ['kos' => $kos, 'variant' => 'grid'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php $__currentLoopData = $latestKos->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4"><?php echo $__env->make('components.kos-card', ['kos' => $kos, 'variant' => 'grid'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KosKu_UAS_FINAL_UIUX_PRO\resources\views/home.blade.php ENDPATH**/ ?>
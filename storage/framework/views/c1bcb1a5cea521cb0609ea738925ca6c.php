<?php $__env->startSection('title', 'Cari Kos'); ?>
<?php $__env->startSection('content'); ?>
<?php
    $selectedFacilities = collect((array) request('fasilitas'))->filter()->map(fn($v) => (string) $v)->values()->all();
    $sortLabels = ['latest'=>'Terbaru','cheap'=>'Termurah','rating'=>'Rating tertinggi','premium'=>'Rekomendasi'];
    $activeSort = request('sort', 'latest');
    $budgetMax = request()->filled('harga_maks') ? (int) preg_replace('/[^0-9]/', '', request('harga_maks')) : $selectedHargaMaks;
    $budgetMax = max($priceMin, min($priceMax, $budgetMax ?: $priceMax));
?>

<section class="simple-listing-head compact-listing-head modern-listing-head">
    <div class="container">
        <span class="section-kicker">CARI KOS</span>
        <h1>Temukan kos sesuai kebutuhan Anda</h1>
        <p>Gunakan pencarian singkat untuk melihat pilihan kos berdasarkan area, budget, tipe, dan ketersediaan kamar.</p>
    </div>
</section>

<div class="container clean-listing-page py-4">
    <form class="modern-filter-bar" action="<?php echo e(route('kos.index')); ?>">
        <div class="modern-filter-main">
            <label class="modern-filter-field wide">
                <span>Nama / lokasi</span>
                <div>
                    <i class="bi bi-search"></i>
                    <input name="q" value="<?php echo e(request('q')); ?>" placeholder="Contoh: Palu Timur, Kos Melati">
                </div>
            </label>

            <label class="modern-filter-field">
                <span>Area</span>
                <select name="area">
                    <option value="">Semua area</option>
                    <?php $__currentLoopData = $areaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($area); ?>" <?php if(request('area')===$area): echo 'selected'; endif; ?>><?php echo e($area); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </label>

            <label class="modern-filter-field">
                <span>Tipe</span>
                <select name="tipe_kos">
                    <option value="">Semua tipe</option>
                    <option value="putra" <?php if(request('tipe_kos')==='putra'): echo 'selected'; endif; ?>>Putra</option>
                    <option value="putri" <?php if(request('tipe_kos')==='putri'): echo 'selected'; endif; ?>>Putri</option>
                    <option value="campur" <?php if(request('tipe_kos')==='campur'): echo 'selected'; endif; ?>>Campur</option>
                </select>
            </label>

            <label class="modern-filter-field">
                <span>Budget maksimal</span>
                <input class="js-money-field" name="harga_maks" value="<?php echo e($budgetMax ? 'Rp ' . number_format($budgetMax, 0, ',', '.') : ''); ?>" inputmode="numeric" placeholder="Rp 1.500.000">
            </label>

            <label class="modern-filter-field compact">
                <span>Status</span>
                <select name="status">
                    <option value="">Semua</option>
                    <option value="tersedia" <?php if(request('status')==='tersedia'): echo 'selected'; endif; ?>>Tersedia</option>
                    <option value="penuh" <?php if(request('status')==='penuh'): echo 'selected'; endif; ?>>Penuh</option>
                </select>
            </label>

            <button class="btn btn-primary modern-filter-submit"><i class="bi bi-search me-1"></i> Cari</button>
        </div>

        <div class="modern-filter-secondary">
            <button class="btn btn-link p-0" type="button" data-bs-toggle="collapse" data-bs-target="#facilityFilter" aria-expanded="<?php echo e(count($selectedFacilities) ? 'true' : 'false'); ?>">
                <i class="bi bi-sliders me-1"></i> Fasilitas <?php echo e(count($selectedFacilities) ? '(' . count($selectedFacilities) . ')' : ''); ?>

            </button>
            <a href="<?php echo e(route('kos.index')); ?>">Reset filter</a>
        </div>

        <div class="collapse <?php echo e(count($selectedFacilities) ? 'show' : ''); ?>" id="facilityFilter">
            <div class="modern-facility-filter">
                <?php $__currentLoopData = $fasilitasList->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label><input type="checkbox" name="fasilitas[]" value="<?php echo e($fas->id); ?>" <?php if(in_array((string)$fas->id, $selectedFacilities)): echo 'checked'; endif; ?>><span><?php echo e($fas->nama_fasilitas); ?></span></label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </form>

    <div class="listing-toolbar clean-toolbar modern-results-toolbar">
        <div>
            <h2><?php echo e($kos->total()); ?> kos ditemukan</h2>
            <p><?php echo e(request('area') ? 'Area ' . request('area') : 'Menampilkan semua area'); ?></p>
        </div>
        <form action="<?php echo e(route('kos.index')); ?>" class="sort-box">
            <?php $__currentLoopData = request()->except('sort', 'page'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(is_array($value)): ?>
                    <?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><input type="hidden" name="<?php echo e($key); ?>[]" value="<?php echo e($item); ?>"><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($value); ?>">
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <label>Urutkan</label>
            <select name="sort" onchange="this.form.submit()">
                <?php $__currentLoopData = $sortLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php if($activeSort === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </form>
    </div>

    <div class="listing-results-list clean-results-list modern-results-list">
        <?php $__empty_1 = true; $__currentLoopData = $kos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php echo $__env->make('components.kos-card', ['kos' => $item], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state"><i class="bi bi-search-heart"></i><h4>Kos tidak ditemukan</h4><p>Coba ubah kata kunci, area, atau budget maksimal.</p><a href="<?php echo e(route('kos.index')); ?>" class="btn btn-primary">Lihat Semua Kos</a></div>
        <?php endif; ?>
    </div>

    <div class="mt-4 pagination-wrap"><?php echo e($kos->links('pagination::bootstrap-5')); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KosKu_UAS_FINAL_UIUX_PRO\resources\views/kos/index.blade.php ENDPATH**/ ?>
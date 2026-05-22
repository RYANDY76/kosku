<?php
    $icons = ['WiFi'=>'bi-wifi','AC'=>'bi-snow','Kipas Angin'=>'bi-fan','Tempat Tidur'=>'bi-lamp','Lemari'=>'bi-archive','Kamar Mandi Dalam'=>'bi-droplet','Parkiran'=>'bi-car-front','Dapur Bersama'=>'bi-cup-hot','CCTV'=>'bi-camera-video','Dekat Kampus'=>'bi-mortarboard'];
    $rating = $kos->reviews_avg_rating ?? $kos->rating_average ?? 0;
    $photos = collect([['src' => $kos->foto_url, 'label' => 'Foto Kos']])->merge(($kos->fotos ?? collect())->map(fn($foto) => ['src' => $foto->url, 'label' => $foto->jenis_label]));
    $carouselId = 'kosCardCarousel' . ($variant ?? 'list') . $kos->id;
    $isGrid = ($variant ?? 'list') === 'grid';
?>

<?php if($isGrid): ?>
<article class="listing-grid-card clean-kos-card">
    <a href="<?php echo e(route('kos.show', $kos)); ?>" class="listing-grid-photo">
        <img src="<?php echo e($kos->foto_url); ?>" alt="<?php echo e($kos->nama_kos); ?>" loading="lazy" onerror="this.src='<?php echo e(asset('images/default-kos.jpg')); ?>'">
    </a>
    <div class="listing-grid-body">
        <div class="card-badge-row mb-2">
            <?php if($kos->verification_status === 'approved'): ?><span class="trust-badge"><i class="bi bi-patch-check-fill"></i> Terverifikasi</span><?php endif; ?>
            <span class="type-badge"><?php echo e(ucfirst($kos->tipe_kos ?? 'campur')); ?></span>
        </div>
        <h3><a href="<?php echo e(route('kos.show', $kos)); ?>"><?php echo e($kos->nama_kos); ?></a></h3>
        <p class="listing-grid-location"><i class="bi bi-geo-alt-fill"></i> <?php echo e($kos->lokasi_area ?: \Illuminate\Support\Str::limit($kos->alamat, 42)); ?></p>
        <div class="simple-card-meta">
            <span><i class="bi bi-star-fill"></i> <?php echo e(number_format($rating ?: 0, 1)); ?></span>
            <span class="<?php echo e($kos->status === 'tersedia' ? 'text-success' : 'text-secondary'); ?>"><?php echo e($kos->status === 'tersedia' ? 'Ada kamar' : 'Penuh'); ?></span>
        </div>
    </div>
    <div class="listing-grid-footer">
        <div><small>Harga</small><strong><?php echo e($kos->harga_rupiah); ?></strong><span>/bulan</span></div>
        <a href="<?php echo e(route('kos.show', $kos)); ?>" class="btn btn-primary btn-sm fw-bold">Detail</a>
    </div>
</article>
<?php else: ?>
<article class="listing-result-card clean-kos-card">
    <div class="result-photo-col">
        <div id="<?php echo e($carouselId); ?>" class="carousel slide result-photo-carousel" data-bs-ride="false">
            <div class="carousel-inner">
                <?php $__currentLoopData = $photos->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="carousel-item <?php if($index === 0): ?> active <?php endif; ?>">
                        <a href="<?php echo e(route('kos.show', $kos)); ?>" class="carousel-photo-link" aria-label="Lihat <?php echo e($kos->nama_kos); ?>">
                            <img src="<?php echo e($photo['src']); ?>" alt="<?php echo e($photo['label']); ?> - <?php echo e($kos->nama_kos); ?>" loading="lazy" onerror="this.src='<?php echo e(asset('images/default-kos.jpg')); ?>'">
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php if($photos->count() > 1): ?>
                <button class="carousel-control-prev mini-control" type="button" data-bs-target="#<?php echo e($carouselId); ?>" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                <button class="carousel-control-next mini-control" type="button" data-bs-target="#<?php echo e($carouselId); ?>" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
            <?php endif; ?>
        </div>
    </div>

    <div class="result-main-col">
        <div class="property-title-row simple-title-row">
            <div>
                <div class="card-badge-row mb-2">
                    <?php if($kos->verification_status === 'approved'): ?><span class="trust-badge"><i class="bi bi-patch-check-fill"></i> Terverifikasi</span><?php endif; ?>
                    <span class="type-badge"><?php echo e(ucfirst($kos->tipe_kos ?? 'campur')); ?></span>
                </div>
                <a href="<?php echo e(route('kos.show', $kos)); ?>" class="property-title"><?php echo e($kos->nama_kos); ?></a>
                <div class="simple-card-meta mt-1">
                    <span><i class="bi bi-star-fill text-warning"></i> <?php echo e(number_format($rating ?: 0, 1)); ?></span>
                    <span><?php echo e(ucfirst($kos->tipe_kos ?? 'campur')); ?></span>
                    <span class="<?php echo e($kos->status === 'tersedia' ? 'text-success' : 'text-secondary'); ?>"><?php echo e($kos->status === 'tersedia' ? 'Ada kamar' : 'Penuh'); ?></span>
                </div>
            </div>
        </div>
        <div class="property-location"><i class="bi bi-geo-alt-fill"></i> <?php echo e($kos->lokasi_area ?: \Illuminate\Support\Str::limit($kos->alamat, 60)); ?> <?php if($kos->jarak_kampus): ?> · <?php echo e($kos->jarak_kampus); ?> km dari kampus <?php endif; ?></div>
        <p class="property-quote simple-desc"><?php echo e(\Illuminate\Support\Str::limit($kos->deskripsi, 95)); ?></p>
        <div class="property-facilities compact simple-facilities">
            <?php $__currentLoopData = $kos->fasilitas->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span><i class="bi <?php echo e($icons[$fas->nama_fasilitas] ?? 'bi-check-circle'); ?>"></i><?php echo e($fas->nama_fasilitas); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="result-price-col simple-price-col">
        <small>Harga per bulan</small>
        <div class="current-price"><?php echo e($kos->harga_rupiah); ?></div>
        <a href="<?php echo e(route('kos.show', $kos)); ?>" class="btn btn-primary w-100 fw-bold mt-2">Lihat Detail</a>
        <a href="<?php echo e($kos->whats_app_url); ?>" target="_blank" class="btn btn-success w-100 fw-bold mt-2"><i class="bi bi-whatsapp"></i> Hubungi</a>
    </div>
</article>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\KosKu_UAS_FINAL_UIUX_PRO\resources\views/components/kos-card.blade.php ENDPATH**/ ?>
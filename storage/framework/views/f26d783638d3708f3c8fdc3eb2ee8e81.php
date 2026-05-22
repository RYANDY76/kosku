<?php $__env->startSection('title', $kos->nama_kos); ?>

<?php $__env->startSection('content'); ?>
<?php
    $rating = $ratingAverage ?? $kos->rating_average;
    $totalReviews = $reviewCount ?? $kos->reviews()->count();
    $reviews = $latestReviews ?? collect();
    $roomPhoto = $kos->fotos->firstWhere('jenis', 'kamar') ?? $kos->fotos->get(0);
    $kitchenPhoto = $kos->fotos->firstWhere('jenis', 'dapur') ?? $kos->fotos->get(1);
    $parkingPhoto = $kos->fotos->firstWhere('jenis', 'parkiran') ?? $kos->fotos->get(2);
    $displayPhotos = collect([
        ['label' => 'Tampak Depan', 'src' => $kos->foto_url],
        ['label' => 'Foto Kamar', 'src' => optional($roomPhoto)->url ?: $kos->foto_url],
        ['label' => 'Foto Dapur', 'src' => optional($kitchenPhoto)->url ?: $kos->foto_url],
        ['label' => 'Foto Parkiran', 'src' => optional($parkingPhoto)->url ?: $kos->foto_url],
    ]);
    $availableRooms = $kos->kamar->where('status', 'tersedia')->sum('jumlah_kamar');
    $lowestRoomPrice = $kos->kamar->min('harga') ?: $kos->harga;
?>

<div class="kos-detail-friendly container py-4">
    <a href="<?php echo e(route('kos.index')); ?>" class="back-link-simple"><i class="bi bi-arrow-left"></i> Kembali ke daftar kos</a>

    <section class="detail-top-simple">
        <div class="detail-photo-area">
            <div id="detailCarousel" class="carousel slide gallery-carousel" data-bs-ride="false">
                <div class="carousel-inner">
                    <?php $__currentLoopData = $displayPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="carousel-item <?php if($index === 0): ?> active <?php endif; ?>">
                            <button type="button" class="gallery-main border-0 p-0 bg-transparent w-100" data-bs-toggle="modal" data-bs-target="#photoModal" data-photo-src="<?php echo e($item['src']); ?>" data-photo-label="<?php echo e($item['label']); ?>">
                                <img loading="lazy" src="<?php echo e($item['src']); ?>" alt="<?php echo e($item['label']); ?> - <?php echo e($kos->nama_kos); ?>" onerror="this.src='<?php echo e(asset('images/default-kos.jpg')); ?>'">
                            </button>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#detailCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span><span class="visually-hidden">Previous</span></button>
                <button class="carousel-control-next" type="button" data-bs-target="#detailCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span><span class="visually-hidden">Next</span></button>
            </div>
            <div class="gallery-thumbs gallery-thumbs-slider" id="galleryThumbs">
                <?php $__currentLoopData = $displayPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" class="gallery-thumb-btn <?php if($index === 0): ?> active <?php endif; ?>" data-bs-target="#detailCarousel" data-bs-slide-to="<?php echo e($index); ?>">
                        <img loading="lazy" src="<?php echo e($item['src']); ?>" alt="<?php echo e($item['label']); ?>" onerror="this.src='<?php echo e(asset('images/default-kos.jpg')); ?>'">
                        <span><?php echo e($item['label']); ?></span>
                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <aside class="detail-action-card">
            <div class="status-row-simple">
                <?php if($kos->verification_status === 'approved'): ?><span class="trust-badge"><i class="bi bi-patch-check-fill"></i> Terverifikasi</span><?php endif; ?>
                <span class="type-pill"><?php echo e(ucfirst($kos->tipe_kos)); ?></span>
                <span class="availability <?php echo e($kos->status === 'tersedia' ? 'available' : 'full'); ?> static"><?php echo e($kos->status_label); ?></span>
            </div>
            <h1><?php echo e($kos->nama_kos); ?></h1>
            <div class="rating-line"><i class="bi bi-star-fill"></i> <?php echo e(number_format($rating, 1)); ?> dari <?php echo e($totalReviews); ?> review</div>
            <p class="detail-location"><i class="bi bi-geo-alt-fill"></i> <?php echo e($kos->alamat); ?></p>
            <?php if($kos->jarak_kampus): ?><p class="text-muted mb-2"><i class="bi bi-mortarboard"></i> Sekitar <?php echo e($kos->jarak_kampus); ?> km dari kampus</p><?php endif; ?>
            <div class="detail-price"><?php echo e('Rp ' . number_format($lowestRoomPrice, 0, ',', '.')); ?><span>/bulan</span></div>
            <div class="detail-decision-list" aria-label="Ringkasan kos">
                <div><i class="bi bi-door-open"></i><span>Kamar tersedia</span><strong><?php echo e($availableRooms); ?> unit</strong></div>
                <div><i class="bi bi-house-check"></i><span>Tipe kos</span><strong><?php echo e(ucfirst($kos->tipe_kos)); ?></strong></div>
                <div><i class="bi bi-geo-alt"></i><span>Area</span><strong><?php echo e($kos->lokasi_area ?: 'Palu'); ?></strong></div>
            </div>
            <div class="d-grid gap-2 detail-cta-group">
                <?php if(auth()->guard()->check()): ?>
                    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#bookingModal"><i class="bi bi-calendar2-check me-1"></i> Ajukan Sewa</button>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-primary btn-lg"><i class="bi bi-box-arrow-in-right me-1"></i> Login untuk Booking</a>
                <?php endif; ?>
                <a target="_blank" href="<?php echo e($kos->whats_app_url); ?>" class="btn btn-outline-success"><i class="bi bi-whatsapp me-1"></i> Chat Pemilik</a>
            </div>
            <div class="owner-box mt-3">
                <i class="bi bi-person-badge"></i>
                <div><strong><a href="<?php echo e(route('pemilik.public.show', $kos->pemilik)); ?>"><?php echo e($kos->pemilik->name ?? 'Pemilik Kos'); ?></a></strong><span><?php echo e($kos->no_wa); ?></span><small><?php echo e($kos->pemilik->profile->alamat ?? 'Profil pemilik belum lengkap'); ?></small></div>
            </div>
        </aside>
    </section>

    <section class="detail-info-grid mt-4">
        <div class="content-card simple-detail-card main-info-card">
            <h3>Informasi Kos</h3>
            <p><?php echo e($kos->deskripsi); ?></p>
            <div class="property-info-notes">
                <span><i class="bi bi-check2-circle"></i> Data kos terhubung dengan pemilik</span>
                <span><i class="bi bi-map"></i> Lokasi dapat dicek melalui maps</span>
                <span><i class="bi bi-receipt"></i> Booking dan pembayaran dipantau di akun user</span>
            </div>
            <div class="facility-list mt-3">
                <?php $__currentLoopData = $kos->fasilitas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><span><i class="bi bi-check-circle-fill"></i><?php echo e($fas->nama_fasilitas); ?></span><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <div class="content-card simple-detail-card side-info-card map-detail-card">
            <h3>Lokasi Kos</h3>
            <p class="text-muted mb-3"><i class="bi bi-geo-alt-fill text-primary"></i> <?php echo e($kos->alamat); ?></p>
            <div class="map-embed-box">
                <iframe loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://maps.google.com/maps?q=<?php echo e(urlencode($kos->alamat)); ?>&output=embed" title="Peta lokasi <?php echo e($kos->nama_kos); ?>"></iframe>
            </div>
            <a target="_blank" href="https://www.google.com/maps/search/?api=1&query=<?php echo e(urlencode($kos->alamat)); ?>" class="btn btn-outline-primary w-100 mt-3"><i class="bi bi-map"></i> Buka di Google Maps</a>
        </div>
    </section>

    <section class="content-card room-section-simple mt-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div><span class="section-kicker">KAMAR TERSEDIA</span><h3 class="mb-0">Pilih Kamar</h3></div>
            <?php if(auth()->guard()->check()): ?> <?php if(auth()->user()->role === 'admin' || auth()->id() === $kos->user_id): ?><a href="<?php echo e(route('dashboard.kelola-kos.kamar.create', $kos)); ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah Kamar</a><?php endif; ?> <?php endif; ?>
        </div>
        <div class="room-list-horizontal">
            <?php $__empty_1 = true; $__currentLoopData = $kos->kamar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kamar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <article class="room-horizontal-card">
                    <button type="button" class="room-horizontal-photo" data-bs-toggle="modal" data-bs-target="#photoModal" data-photo-src="<?php echo e($kamar->foto_url); ?>" data-photo-label="<?php echo e($kamar->kode_kamar ?: $kamar->tipe_kamar); ?>">
                        <img loading="lazy" src="<?php echo e($kamar->foto_url); ?>" alt="<?php echo e($kamar->tipe_kamar); ?>" onerror="this.src='<?php echo e(asset('images/default-kos.jpg')); ?>'">
                        <span class="room-status <?php echo e($kamar->status === 'tersedia' ? 'available' : 'full'); ?>"><?php echo e($kamar->status === 'tersedia' ? 'Kosong' : 'Terisi'); ?></span>
                    </button>
                    <div class="room-horizontal-info">
                        <h4><?php echo e($kamar->kode_kamar ?: 'Unit Kos'); ?> <span><?php echo e($kamar->tipe_kamar); ?></span></h4>
                        <div class="room-meta"><span>Lantai <?php echo e($kamar->lantai ?: '-'); ?></span><span><?php echo e($kamar->luas_kamar ?: '-'); ?> m²</span><span><?php echo e($kamar->jumlah_kamar); ?> unit</span></div>
                        <p><?php echo e($kamar->catatan ?: 'Kamar siap huni dengan fasilitas sesuai deskripsi kos.'); ?></p>
                    </div>
                    <div class="room-horizontal-price">
                        <small>Harga per bulan</small>
                        <strong><?php echo e($kamar->harga_rupiah); ?></strong>
                        <?php if(auth()->guard()->check()): ?>
                            <?php if(auth()->user()->role === 'admin' || auth()->id() === $kos->user_id): ?>
                                <a class="btn btn-warning btn-sm w-100" href="<?php echo e(route('dashboard.kelola-kos.kamar.edit', [$kos, $kamar])); ?>"><i class="bi bi-pencil"></i> Edit</a>
                            <?php else: ?>
                                <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#bookingModal" data-kamar-id="<?php echo e($kamar->id); ?>">Ajukan Sewa</button>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary btn-sm w-100">Login untuk Sewa</a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-mini"><i class="bi bi-door-open"></i><strong>Belum ada data kamar</strong><span>Pemilik/admin dapat menambahkan unit kamar dari dashboard.</span></div>
            <?php endif; ?>
        </div>
    </section>

    <section class="content-card mt-4">
        <div class="d-flex justify-content-between align-items-start gap-3 mb-3"><div><span class="section-kicker">ULASAN PENYEWA</span><h3 class="mb-0">Review & Rating</h3></div><div class="rating-line"><i class="bi bi-star-fill"></i> <?php echo e(number_format($rating, 1)); ?></div></div>
        <?php if(auth()->guard()->check()): ?>
            <?php if(auth()->id() !== $kos->user_id): ?>
                <?php
                    $hasApprovedBooking = auth()->user()->bookings()
                        ->where('kos_id', $kos->id)
                        ->where('status', 'approved')
                        ->exists();
                ?>
                <?php if($hasApprovedBooking): ?>
                    <form method="POST" action="<?php echo e(route('dashboard.reviews.store', $kos)); ?>" class="review-form mb-4">
                        <?php echo csrf_field(); ?>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <select name="rating" class="form-select" required>
                                    <?php for($i = 5; $i >= 1; $i--): ?>
                                        <option value="<?php echo e($i); ?>" <?php if(optional($userReview)->rating == $i): echo 'selected'; endif; ?>><?php echo e($i); ?> Bintang</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <input name="komentar" class="form-control" value="<?php echo e(old('komentar', optional($userReview)->komentar)); ?>" placeholder="Tulis pengalamanmu (min. 8 karakter)" required>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100"><?php echo e($userReview ? 'Update' : 'Kirim'); ?></button>
                            </div>
                        </div>
                        <?php if($userReview): ?>
                            <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i>Kamu sudah pernah review. Submit akan memperbarui review lama.</small>
                        <?php endif; ?>
                    </form>
                <?php else: ?>
                    <div class="alert alert-light border mb-4 py-2 px-3">
                        <i class="bi bi-lock me-1 text-muted"></i>
                        <small class="text-muted">Review hanya bisa diberikan setelah booking kamu diterima oleh pemilik kos.</small>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-light border mb-4 py-2 px-3">
                <i class="bi bi-person me-1 text-muted"></i>
                <small class="text-muted"><a href="<?php echo e(route('login')); ?>">Login</a> untuk memberikan review.</small>
            </div>
        <?php endif; ?>

        <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="review-item">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
                    <div>
                        <strong><?php echo e($review->user->name ?? 'Pengguna'); ?></strong>
                        <span class="ms-2">
                            <?php for($star = 1; $star <= 5; $star++): ?>
                                <i class="bi <?php echo e($star <= $review->rating ? 'bi-star-fill text-warning' : 'bi-star text-muted'); ?>"></i>
                            <?php endfor; ?>
                        </span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <small class="text-muted"><?php echo e($review->created_at->diffForHumans()); ?></small>
                        <?php if(auth()->guard()->check()): ?>
                            <?php if(auth()->user()->role === 'admin' || $review->user_id === auth()->id()): ?>
                                <form method="POST" action="<?php echo e(route('dashboard.reviews.destroy', $review)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-link btn-sm text-danger p-0 lh-1" data-confirm="Hapus review ini?" title="Hapus review"><i class="bi bi-trash"></i></button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <p class="mt-1 mb-0"><?php echo e($review->komentar); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-muted mb-0">Belum ada review. Jadilah yang pertama!</p>
        <?php endif; ?>

        <?php if($totalReviews > count($reviews)): ?>
            <div class="text-center mt-3">
                <small class="text-muted">Menampilkan <?php echo e(count($reviews)); ?> dari <?php echo e($totalReviews); ?> review.</small>
            </div>
        <?php endif; ?>
    </section>
</div>

<div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-xl modal-dialog-centered"><div class="modal-content border-0 rounded-4 overflow-hidden"><div class="modal-header"><h5 class="modal-title" id="photoModalLabel">Foto Kos</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body p-0 bg-dark text-center"><img id="photoModalImage" src="<?php echo e($kos->foto_url); ?>" alt="Foto kos" class="img-fluid w-100" style="max-height:80vh;object-fit:contain;"></div></div></div></div>

<?php if(auth()->guard()->check()): ?>
<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="<?php echo e(route('dashboard.bookings.store', $kos)); ?>"><?php echo csrf_field(); ?><div class="modal-header"><h5 class="modal-title">Ajukan Sewa <?php echo e($kos->nama_kos); ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="mb-3"><label class="form-label">Pilih Kamar</label><select name="kamar_id" id="bookingKamarSelect" class="form-select"><option value="">Pilih kamar</option><?php $__currentLoopData = $kos->kamar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kamar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($kamar->id); ?>"><?php echo e($kamar->kode_kamar ?: $kamar->tipe_kamar); ?> - <?php echo e($kamar->harga_rupiah); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div><div class="mb-3"><label class="form-label">Nama Pemesan</label><input name="nama_pemesan" value="<?php echo e(auth()->user()->name); ?>" class="form-control" required></div><div class="mb-3"><label class="form-label">No WhatsApp</label><input name="no_wa" value="<?php echo e(auth()->user()->profile->no_wa ?? ''); ?>" class="form-control" placeholder="62812xxxx" required></div><div class="mb-3"><label class="form-label">Rencana Masuk</label><input type="date" name="tanggal_masuk" class="form-control" min="<?php echo e(date('Y-m-d')); ?>"></div><div class="mb-3"><label class="form-label">Catatan</label><textarea name="catatan" rows="3" class="form-control" placeholder="Tulis catatan tambahan bila diperlukan"></textarea></div></div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Kirim Pengajuan</button></div></form></div></div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const detailCarousel = document.getElementById('detailCarousel');
const thumbButtons = document.querySelectorAll('#galleryThumbs .gallery-thumb-btn');
detailCarousel?.addEventListener('slid.bs.carousel', event => thumbButtons.forEach((button, index) => button.classList.toggle('active', index === event.to)));
document.querySelectorAll('[data-kamar-id]').forEach(button => button.addEventListener('click', () => { const select = document.getElementById('bookingKamarSelect'); if (select) select.value = button.getAttribute('data-kamar-id'); }));
document.querySelectorAll('[data-photo-src]').forEach(button => button.addEventListener('click', () => { const image = document.getElementById('photoModalImage'); const title = document.getElementById('photoModalLabel'); image.src = button.dataset.photoSrc; title.textContent = button.dataset.photoLabel || 'Foto Kos'; }));
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KosKu_UAS_FINAL_UIUX_PRO\resources\views/kos/show.blade.php ENDPATH**/ ?>
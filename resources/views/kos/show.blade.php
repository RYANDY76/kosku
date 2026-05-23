@extends('layouts.app')
@section('title', $kos->nama_kos)

@section('content')
@php
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
@endphp

<div class="kos-detail-friendly container py-4">
    <a href="{{ route('kos.index') }}" class="back-link-simple"><i class="bi bi-arrow-left"></i> Kembali ke daftar kos</a>

    <section class="detail-top-simple">
        <div class="detail-photo-area">
            <div id="detailCarousel" class="carousel slide gallery-carousel" data-bs-ride="false">
                <div class="carousel-inner">
                    @foreach($displayPhotos as $index => $item)
                        <div class="carousel-item @if($index === 0) active @endif">
                            <button type="button" class="gallery-main border-0 p-0 bg-transparent w-100" data-bs-toggle="modal" data-bs-target="#photoModal" data-photo-src="{{ $item['src'] }}" data-photo-label="{{ $item['label'] }}">
                                <img loading="lazy" src="{{ $item['src'] }}" alt="{{ $item['label'] }} - {{ $kos->nama_kos }}" onerror="this.src='{{ asset('images/default-kos.jpg') }}'">
                            </button>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#detailCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span><span class="visually-hidden">Previous</span></button>
                <button class="carousel-control-next" type="button" data-bs-target="#detailCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span><span class="visually-hidden">Next</span></button>
            </div>
            <div class="gallery-thumbs gallery-thumbs-slider" id="galleryThumbs">
                @foreach($displayPhotos as $index => $item)
                    <button type="button" class="gallery-thumb-btn @if($index === 0) active @endif" data-bs-target="#detailCarousel" data-bs-slide-to="{{ $index }}">
                        <img loading="lazy" src="{{ $item['src'] }}" alt="{{ $item['label'] }}" onerror="this.src='{{ asset('images/default-kos.jpg') }}'">
                        <span>{{ $item['label'] }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <aside class="detail-action-card">
            <div class="status-row-simple">
                @if($kos->verification_status === 'approved')<span class="trust-badge"><i class="bi bi-patch-check-fill"></i> Terverifikasi</span>@endif
                <span class="type-pill">{{ ucfirst($kos->tipe_kos) }}</span>
                <span class="availability {{ $kos->status === 'tersedia' ? 'available' : 'full' }} static">{{ $kos->status_label }}</span>
            </div>
            <h1>{{ $kos->nama_kos }}</h1>
            <div class="rating-line"><i class="bi bi-star-fill"></i> {{ number_format($rating, 1) }} dari {{ $totalReviews }} review</div>
            <p class="detail-location"><i class="bi bi-geo-alt-fill"></i> {{ $kos->alamat }}</p>
            @if($kos->jarak_kampus)<p class="text-muted mb-2"><i class="bi bi-mortarboard"></i> Sekitar {{ $kos->jarak_kampus }} km dari kampus</p>@endif
            <div class="detail-price">{{ 'Rp ' . number_format($lowestRoomPrice, 0, ',', '.') }}<span>/bulan</span></div>
            <div class="detail-decision-list" aria-label="Ringkasan kos">
                <div><i class="bi bi-door-open"></i><span>Kamar tersedia</span><strong>{{ $availableRooms }} unit</strong></div>
                <div><i class="bi bi-house-check"></i><span>Tipe kos</span><strong>{{ ucfirst($kos->tipe_kos) }}</strong></div>
                <div><i class="bi bi-geo-alt"></i><span>Area</span><strong>{{ $kos->lokasi_area ?: 'Palu' }}</strong></div>
            </div>
            <div class="d-grid gap-2 detail-cta-group">
               @auth
    @if(auth()->user()->role === 'user')
        <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#bookingModal"><i class="bi bi-calendar2-check me-1"></i> Ajukan Sewa</button>
    @elseif(auth()->user()->role === 'pemilik')
        <div class="alert alert-warning py-2 px-3 mb-0 text-center" style="font-size:.9rem">
            <i class="bi bi-info-circle me-1"></i> Pemilik kos tidak dapat melakukan booking.
        </div>
    @endif
    @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg"><i class="bi bi-box-arrow-in-right me-1"></i> Login untuk Booking</a>
                @endauth
                <a target="_blank" href="{{ $kos->whats_app_url }}" class="btn btn-outline-success"><i class="bi bi-whatsapp me-1"></i> Chat Pemilik</a>
            </div>
            <div class="owner-box mt-3">
                <i class="bi bi-person-badge"></i>
                <div><strong><a href="{{ route('pemilik.public.show', $kos->pemilik) }}">{{ $kos->pemilik->name ?? 'Pemilik Kos' }}</a></strong><span>{{ $kos->no_wa }}</span><small>{{ $kos->pemilik->profile->alamat ?? 'Profil pemilik belum lengkap' }}</small></div>
            </div>
        </aside>
    </section>

    <section class="detail-info-grid mt-4">
        <div class="content-card simple-detail-card main-info-card">
            <h3>Informasi Kos</h3>
            <p>{{ $kos->deskripsi }}</p>
            <div class="property-info-notes">
                <span><i class="bi bi-check2-circle"></i> Data kos terhubung dengan pemilik</span>
                <span><i class="bi bi-map"></i> Lokasi dapat dicek melalui maps</span>
                <span><i class="bi bi-receipt"></i> Booking dan pembayaran dipantau di akun user</span>
            </div>
            <div class="facility-list mt-3">
                @foreach($kos->fasilitas as $fas)<span><i class="bi bi-check-circle-fill"></i>{{ $fas->nama_fasilitas }}</span>@endforeach
            </div>
        </div>
        <div class="content-card simple-detail-card side-info-card map-detail-card">
            <h3>Lokasi Kos</h3>
            <p class="text-muted mb-3"><i class="bi bi-geo-alt-fill text-primary"></i> {{ $kos->alamat }}</p>
            <div class="map-embed-box">
                <iframe loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://maps.google.com/maps?q={{ urlencode($kos->alamat) }}&output=embed" title="Peta lokasi {{ $kos->nama_kos }}"></iframe>
            </div>
            <a target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ urlencode($kos->alamat) }}" class="btn btn-outline-primary w-100 mt-3"><i class="bi bi-map"></i> Buka di Google Maps</a>
        </div>
    </section>

    <section class="content-card room-section-simple mt-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div><span class="section-kicker">KAMAR TERSEDIA</span><h3 class="mb-0">Pilih Kamar</h3></div>
            @auth @if(auth()->user()->role === 'admin' || auth()->id() === $kos->user_id)<a href="{{ route('dashboard.kelola-kos.kamar.create', $kos) }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah Kamar</a>@endif @endauth
        </div>
        <div class="room-list-horizontal">
            @forelse($kos->kamar as $kamar)
                <article class="room-horizontal-card">
                    <button type="button" class="room-horizontal-photo" data-bs-toggle="modal" data-bs-target="#photoModal" data-photo-src="{{ $kamar->foto_url }}" data-photo-label="{{ $kamar->kode_kamar ?: $kamar->tipe_kamar }}">
                        <img loading="lazy" src="{{ $kamar->foto_url }}" alt="{{ $kamar->tipe_kamar }}" onerror="this.src='{{ asset('images/default-kos.jpg') }}'">
                        <span class="room-status {{ $kamar->status === 'tersedia' ? 'available' : 'full' }}">{{ $kamar->status === 'tersedia' ? 'Kosong' : 'Terisi' }}</span>
                    </button>
                    <div class="room-horizontal-info">
                        <h4>{{ $kamar->kode_kamar ?: 'Unit Kos' }} <span>{{ $kamar->tipe_kamar }}</span></h4>
                        <div class="room-meta"><span>Lantai {{ $kamar->lantai ?: '-' }}</span><span>{{ $kamar->luas_kamar ?: '-' }} m²</span><span>{{ $kamar->jumlah_kamar }} unit</span></div>
                        <p>{{ $kamar->catatan ?: 'Kamar siap huni dengan fasilitas sesuai deskripsi kos.' }}</p>
                    </div>
                    <div class="room-horizontal-price">
                        <small>Harga per bulan</small>
                        <strong>{{ $kamar->harga_rupiah }}</strong>
                        @auth
                            @if(auth()->user()->role === 'admin' || auth()->id() === $kos->user_id)
                                <a class="btn btn-warning btn-sm w-100" href="{{ route('dashboard.kelola-kos.kamar.edit', [$kos, $kamar]) }}"><i class="bi bi-pencil"></i> Edit</a>
                            @elseif(auth()->user()->role === 'user')
                                <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#bookingModal" data-kamar-id="{{ $kamar->id }}">Ajukan Sewa</button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm w-100">Login untuk Sewa</a>
                        @endauth
                    </div>
                </article>
            @empty
                <div class="empty-mini"><i class="bi bi-door-open"></i><strong>Belum ada data kamar</strong><span>Pemilik/admin dapat menambahkan unit kamar dari dashboard.</span></div>
            @endforelse
        </div>
    </section>

    <section class="content-card mt-4">
        <div class="d-flex justify-content-between align-items-start gap-3 mb-3"><div><span class="section-kicker">ULASAN PENYEWA</span><h3 class="mb-0">Review & Rating</h3></div><div class="rating-line"><i class="bi bi-star-fill"></i> {{ number_format($rating, 1) }}</div></div>
        @auth
            @if(auth()->id() !== $kos->user_id)
                @php
                    $hasApprovedBooking = auth()->user()->bookings()
                        ->where('kos_id', $kos->id)
                        ->where('status', 'approved')
                        ->exists();
                @endphp
                @if($hasApprovedBooking)
                    <form method="POST" action="{{ route('dashboard.reviews.store', $kos) }}" class="review-form mb-4">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-3">
                                <select name="rating" class="form-select" required>
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" @selected(optional($userReview)->rating == $i)>{{ $i }} Bintang</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-7">
                                <input name="komentar" class="form-control" value="{{ old('komentar', optional($userReview)->komentar) }}" placeholder="Tulis pengalamanmu (min. 8 karakter)" required>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100">{{ $userReview ? 'Update' : 'Kirim' }}</button>
                            </div>
                        </div>
                        @if($userReview)
                            <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i>Kamu sudah pernah review. Submit akan memperbarui review lama.</small>
                        @endif
                    </form>
                @else
                    <div class="alert alert-light border mb-4 py-2 px-3">
                        <i class="bi bi-lock me-1 text-muted"></i>
                        <small class="text-muted">Review hanya bisa diberikan setelah booking kamu diterima oleh pemilik kos.</small>
                    </div>
                @endif
            @endif
        @else
            <div class="alert alert-light border mb-4 py-2 px-3">
                <i class="bi bi-person me-1 text-muted"></i>
                <small class="text-muted"><a href="{{ route('login') }}">Login</a> untuk memberikan review.</small>
            </div>
        @endauth

        @forelse($reviews as $review)
            <div class="review-item">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
                    <div>
                        <strong>{{ $review->user->name ?? 'Pengguna' }}</strong>
                        <span class="ms-2">
                            @for($star = 1; $star <= 5; $star++)
                                <i class="bi {{ $star <= $review->rating ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                            @endfor
                        </span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        @auth
                            @if(auth()->user()->role === 'admin' || $review->user_id === auth()->id())
                                <form method="POST" action="{{ route('dashboard.reviews.destroy', $review) }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-link btn-sm text-danger p-0 lh-1" data-confirm="Hapus review ini?" title="Hapus review"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
                <p class="mt-1 mb-0">{{ $review->komentar }}</p>
            </div>
        @empty
            <p class="text-muted mb-0">Belum ada review. Jadilah yang pertama!</p>
        @endforelse

        @if($totalReviews > count($reviews))
            <div class="text-center mt-3">
                <small class="text-muted">Menampilkan {{ count($reviews) }} dari {{ $totalReviews }} review.</small>
            </div>
        @endif
    </section>
</div>

<div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-xl modal-dialog-centered"><div class="modal-content border-0 rounded-4 overflow-hidden"><div class="modal-header"><h5 class="modal-title" id="photoModalLabel">Foto Kos</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body p-0 bg-dark text-center"><img id="photoModalImage" src="{{ $kos->foto_url }}" alt="Foto kos" class="img-fluid w-100" style="max-height:80vh;object-fit:contain;"></div></div></div></div>

@auth
@if(auth()->user()->role === 'user')
<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('dashboard.bookings.store', $kos) }}">@csrf<div class="modal-header"><h5 class="modal-title">Ajukan Sewa {{ $kos->nama_kos }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="mb-3"><label class="form-label">Pilih Kamar</label><select name="kamar_id" id="bookingKamarSelect" class="form-select"><option value="">Pilih kamar</option>@foreach($kos->kamar as $kamar)<option value="{{ $kamar->id }}">{{ $kamar->kode_kamar ?: $kamar->tipe_kamar }} - {{ $kamar->harga_rupiah }}</option>@endforeach</select></div><div class="mb-3"><label class="form-label">Nama Pemesan</label><input name="nama_pemesan" value="{{ auth()->user()->name }}" class="form-control" required></div><div class="mb-3"><label class="form-label">No WhatsApp</label><input name="no_wa" value="{{ auth()->user()->profile->no_wa ?? '' }}" class="form-control" placeholder="62812xxxx" required></div><div class="mb-3"><label class="form-label">Rencana Masuk</label><input type="date" name="tanggal_masuk" class="form-control" min="{{ date('Y-m-d') }}"></div><div class="mb-3"><label class="form-label">Catatan</label><textarea name="catatan" rows="3" class="form-control" placeholder="Tulis catatan tambahan bila diperlukan"></textarea></div></div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary">Kirim Pengajuan</button></div></form></div></div>
@endif
@endauth
@endsection

@push('scripts')
<script>
const detailCarousel = document.getElementById('detailCarousel');
const thumbButtons = document.querySelectorAll('#galleryThumbs .gallery-thumb-btn');
detailCarousel?.addEventListener('slid.bs.carousel', event => thumbButtons.forEach((button, index) => button.classList.toggle('active', index === event.to)));
document.querySelectorAll('[data-kamar-id]').forEach(button => button.addEventListener('click', () => { const select = document.getElementById('bookingKamarSelect'); if (select) select.value = button.getAttribute('data-kamar-id'); }));
document.querySelectorAll('[data-photo-src]').forEach(button => button.addEventListener('click', () => { const image = document.getElementById('photoModalImage'); const title = document.getElementById('photoModalLabel'); image.src = button.dataset.photoSrc; title.textContent = button.dataset.photoLabel || 'Foto Kos'; }));
</script>
@endpush
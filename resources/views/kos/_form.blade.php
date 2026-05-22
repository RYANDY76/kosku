@csrf
@php
    $photoKamar = isset($kos) ? $kos->fotos->firstWhere('jenis', 'kamar') : null;
    $photoDapur = isset($kos) ? $kos->fotos->firstWhere('jenis', 'dapur') : null;
    $photoParkiran = isset($kos) ? $kos->fotos->firstWhere('jenis', 'parkiran') : null;
@endphp
<div class="row g-4">
    <div class="col-lg-8"><div class="card border-0 shadow-sm form-wizard"><div class="card-body p-4"><span class="step-badge">Step 1</span><h5 class="fw-bold mb-3">Informasi Utama Kos</h5><div class="row g-3">
        <div class="col-md-6"><label class="form-label">Nama Kos</label><input type="text" name="nama_kos" value="{{ old('nama_kos', $kos->nama_kos ?? '') }}" class="form-control" required></div>
        <div class="col-md-3"><label class="form-label">Tipe Kos</label><select name="tipe_kos" class="form-select"><option value="campur" @selected(old('tipe_kos', $kos->tipe_kos ?? '')==='campur')>Campur</option><option value="putra" @selected(old('tipe_kos', $kos->tipe_kos ?? '')==='putra')>Putra</option><option value="putri" @selected(old('tipe_kos', $kos->tipe_kos ?? '')==='putri')>Putri</option></select></div>
        <div class="col-md-3"><label class="form-label">Status Kamar</label><select name="status" class="form-select"><option value="tersedia" @selected(old('status', $kos->status ?? '')==='tersedia')>Ada Kosong</option><option value="penuh" @selected(old('status', $kos->status ?? '')==='penuh')>Penuh</option></select></div>
        <div class="col-md-6"><label class="form-label">Area/Lokasi</label><input type="text" name="lokasi_area" value="{{ old('lokasi_area', $kos->lokasi_area ?? '') }}" class="form-control" placeholder="Palu Timur, Kampus Untad..."></div>
        <div class="col-md-3"><label class="form-label">Jarak Kampus (km)</label><input type="number" step="0.1" name="jarak_kampus" value="{{ old('jarak_kampus', $kos->jarak_kampus ?? '') }}" class="form-control" placeholder="1.5"></div>
        <div class="col-md-3"><label class="form-label">Nomor WhatsApp</label><input type="text" name="no_wa" value="{{ old('no_wa', $kos->no_wa ?? '') }}" class="form-control" placeholder="62812xxxx" required></div>
        <div class="col-12"><label class="form-label">Alamat Lengkap</label><textarea name="alamat" rows="3" class="form-control" required>{{ old('alamat', $kos->alamat ?? '') }}</textarea></div>
        <div class="col-12"><label class="form-label">Deskripsi</label><textarea name="deskripsi" rows="4" class="form-control" required>{{ old('deskripsi', $kos->deskripsi ?? '') }}</textarea></div>
        <div class="col-md-6"><label class="form-label">Harga per Bulan</label><div class="input-group"><span class="input-group-text">Rp</span><input type="number" name="harga" value="{{ old('harga', $kos->harga ?? '') }}" class="form-control js-price-number" placeholder="1000000" step="50000" min="0" required></div><div class="quick-price-buttons" data-target="harga"><button type="button" data-price="650000">650 rb</button><button type="button" data-price="800000">800 rb</button><button type="button" data-price="1000000">1 jt</button><button type="button" data-price="1500000">1,5 jt</button></div></div>

        <div class="col-12"><div class="alert alert-light border rounded-4 mb-0"><strong>Galeri 4 Foto Utama</strong><div class="small text-muted mt-1">Setiap kos sekarang memakai 4 foto tetap: foto 1 tampak depan, foto 2 kamar, foto 3 dapur, dan foto 4 parkiran. Semua foto ini akan tampil bisa dipencet dan di-slide pada halaman detail kos.</div></div></div>
        <div class="col-md-6"><label class="form-label">Foto 1 - Tampak Depan</label><input type="file" name="foto" class="form-control" accept="image/*" id="fotoInput"><div class="form-text">JPG/PNG/WEBP maksimal 4MB.</div></div>
        <div class="col-md-6"><label class="form-label">Foto 2 - Kamar</label><input type="file" name="foto_kamar" class="form-control" accept="image/*"><div class="form-text">Gunakan foto kamar yang jelas dan terang.</div></div>
        <div class="col-md-6"><label class="form-label">Foto 3 - Dapur</label><input type="file" name="foto_dapur" class="form-control" accept="image/*"><div class="form-text">Gunakan foto area dapur atau pantry.</div></div>
        <div class="col-md-6"><label class="form-label">Foto 4 - Parkiran</label><input type="file" name="foto_parkiran" class="form-control" accept="image/*"><div class="form-text">Gunakan foto area parkir kos.</div></div>

        @if(auth()->user()->role === 'admin')
            <div class="col-md-6"><label class="form-label">Status Verifikasi</label><select name="verification_status" class="form-select"><option value="pending" @selected(old('verification_status', $kos->verification_status ?? '')==='pending')>Menunggu</option><option value="approved" @selected(old('verification_status', $kos->verification_status ?? 'approved')==='approved')>Disetujui</option><option value="rejected" @selected(old('verification_status', $kos->verification_status ?? '')==='rejected')>Ditolak</option></select></div>
            <div class="col-md-6 d-flex align-items-end"><label class="facility-check d-inline-flex w-100"><input type="checkbox" name="premium" value="1" @checked(old('premium', $kos->premium ?? false))><span><i class="bi bi-star-fill me-2"></i>Tandai sebagai Premium/Rekomendasi</span></label></div>
        @else
            <div class="col-12"><div class="alert alert-info mb-0"><i class="bi bi-shield-check me-1"></i> Kos baru/diubah oleh pemilik akan tampil setelah diverifikasi admin. Label Premium hanya bisa diatur admin.</div></div>
        @endif
    </div></div></div></div>
    <div class="col-lg-4"><div class="card border-0 shadow-sm sticky-lg-top sticky-form"><div class="card-body p-4"><span class="step-badge">Step 2</span><h5 class="fw-bold mb-3">Fasilitas Kos</h5><div class="row g-2">@foreach($fasilitas as $item)<div class="col-12"><label class="facility-check w-100"><input type="checkbox" name="fasilitas[]" value="{{ $item->id }}" @checked(in_array($item->id, old('fasilitas', isset($kos) ? $kos->fasilitas->pluck('id')->toArray() : [])))><span><i class="bi bi-check2-circle me-2"></i>{{ $item->nama_fasilitas }}</span></label></div>@endforeach</div><div class="preview-box mt-3"><img id="previewImage" src="{{ isset($kos) ? $kos->foto_url : asset('images/default-kos.jpg') }}" alt="Preview"></div>
        @isset($kos)
            <div class="mini-gallery mt-3 four-photo-grid">
                <div><img loading="lazy" src="{{ $kos->foto_url }}" alt="depan"><small>Tampak Depan</small></div>
                <div><img loading="lazy" src="{{ $photoKamar?->url ?? asset('images/default-kos.jpg') }}" alt="kamar"><small>Kamar</small></div>
                <div><img loading="lazy" src="{{ $photoDapur?->url ?? asset('images/default-kos.jpg') }}" alt="dapur"><small>Dapur</small></div>
                <div><img loading="lazy" src="{{ $photoParkiran?->url ?? asset('images/default-kos.jpg') }}" alt="parkiran"><small>Parkiran</small></div>
            </div>
        @endisset
        <div class="d-grid gap-2 mt-4"><button class="btn btn-orange btn-lg"><i class="bi bi-save me-1"></i>Simpan Kos</button><a href="{{ route('dashboard.index') }}" class="btn btn-outline-secondary">Batal</a></div></div></div></div>
</div>
@push('scripts')<script>document.getElementById('fotoInput')?.addEventListener('change',e=>{const f=e.target.files[0]; if(f){document.getElementById('previewImage').src=URL.createObjectURL(f);}}); document.querySelectorAll('.quick-price-buttons button').forEach(btn=>btn.addEventListener('click',()=>{const wrap=btn.closest('.quick-price-buttons'); const input=document.querySelector('[name=' + wrap.dataset.target + ']'); if(input) input.value=btn.dataset.price;}));</script>@endpush

@csrf
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Kode Kamar / Unit</label>
        <input type="text" name="kode_kamar" value="{{ old('kode_kamar', $kamar->kode_kamar ?? '') }}" class="form-control" placeholder="A1 / B2 / K-01">
    </div>
    <div class="col-md-4">
        <label class="form-label">Tipe Kamar</label>
        <input type="text" name="tipe_kamar" value="{{ old('tipe_kamar', $kamar->tipe_kamar ?? '') }}" class="form-control" placeholder="Standard / Superior / Deluxe" required>
    </div>
    <div class="col-md-2">
        <label class="form-label">Lantai</label>
        <input type="number" name="lantai" value="{{ old('lantai', $kamar->lantai ?? 1) }}" class="form-control">
    </div>
    <div class="col-md-2">
        <label class="form-label">Luas m²</label>
        <input type="number" step="0.1" name="luas_kamar" value="{{ old('luas_kamar', $kamar->luas_kamar ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Harga Bulanan</label>
        <div class="input-group"><span class="input-group-text">Rp</span><input type="number" name="harga" value="{{ old('harga', $kamar->harga ?? '') }}" class="form-control js-price-number" step="50000" min="0" placeholder="1000000" required></div><div class="quick-price-buttons" data-target="harga"><button type="button" data-price="650000">650 rb</button><button type="button" data-price="800000">800 rb</button><button type="button" data-price="1000000">1 jt</button><button type="button" data-price="1500000">1,5 jt</button></div>
    </div>
    <div class="col-md-4">
        <label class="form-label">Harga Harian <small class="text-muted">(opsional)</small></label>
        <div class="input-group"><span class="input-group-text">Rp</span><input type="number" name="harga_harian" value="{{ old('harga_harian', $kamar->harga_harian ?? '') }}" class="form-control"></div>
    </div>
    <div class="col-md-4">
        <label class="form-label">Harga Tahunan <small class="text-muted">(opsional)</small></label>
        <div class="input-group"><span class="input-group-text">Rp</span><input type="number" name="harga_tahunan" value="{{ old('harga_tahunan', $kamar->harga_tahunan ?? '') }}" class="form-control"></div>
    </div>
    <div class="col-md-3">
        <label class="form-label">Jumlah Kamar</label>
        <input type="number" name="jumlah_kamar" value="{{ old('jumlah_kamar', $kamar->jumlah_kamar ?? 1) }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="tersedia" @selected(old('status', $kamar->status ?? '') === 'tersedia')>Tersedia</option>
            <option value="penuh" @selected(old('status', $kamar->status ?? '') === 'penuh')>Penuh</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Foto Kamar</label>
        <input type="file" name="foto_file" class="form-control" accept="image/*" id="fotoKamarInput">
        <input type="hidden" name="foto" value="{{ old('foto', $kamar->foto ?? '') }}">
        <div class="form-text">Upload JPG, PNG, atau WEBP maksimal 4MB. Jika kosong, foto lama tetap digunakan.</div>
        <div class="preview-box mt-2 small-room-preview"><img id="previewKamar" src="{{ isset($kamar) && $kamar->foto ? (str_starts_with($kamar->foto, 'images/') ? asset($kamar->foto) : asset('storage/' . $kamar->foto)) : asset('images/default-kos.jpg') }}" alt="Preview kamar"></div>
    </div>
    <div class="col-12">
        <label class="form-label">Catatan</label>
        <input type="text" name="catatan" value="{{ old('catatan', $kamar->catatan ?? '') }}" class="form-control" placeholder="Catatan fasilitas atau kondisi kamar">
    </div>
</div>
<div class="mt-4">
    <button class="btn btn-success"><i class="bi bi-save me-1"></i>Simpan Kamar</button>
    <a href="{{ route('kos.show', $kos) }}" class="btn btn-outline-secondary">Batal</a>
</div>

@push('scripts')<script>document.querySelectorAll('.quick-price-buttons button').forEach(btn=>btn.addEventListener('click',()=>{const wrap=btn.closest('.quick-price-buttons'); const input=document.querySelector('[name=' + wrap.dataset.target + ']'); if(input) input.value=btn.dataset.price;})); document.getElementById('fotoKamarInput')?.addEventListener('change', e => { const f=e.target.files[0]; if(f) document.getElementById('previewKamar').src=URL.createObjectURL(f); });</script>@endpush

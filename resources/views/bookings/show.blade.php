@extends(auth()->user()->role === 'user' ? 'layouts.app' : 'layouts.dashboard')
@section('title', 'Detail Booking')
@section('page_title', 'Detail Booking')
@section('content')
<section class="booking-detail-shell {{ auth()->user()->role === 'user' ? 'container py-5' : '' }}">
    <div class="booking-detail-head">
        <div>
            <span class="section-kicker">DETAIL BOOKING</span>
            <h2>{{ $booking->kos->nama_kos ?? 'Kos' }}</h2>
            <p>Ringkasan pengajuan sewa, kamar, dan status pembayaran.</p>
        </div>
        <span class="badge text-bg-{{ $booking->status_badge }} fs-6">{{ $booking->status_label }}</span>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="profile-detail-card h-100">
                <h3 class="mb-3">Informasi Pengajuan</h3>
                <div class="booking-detail-grid">
                    <div><span>Pemesan</span><strong>{{ $booking->nama_pemesan }}</strong><small>{{ $booking->user->email ?? '-' }}</small></div>
                    <div><span>No WhatsApp</span><strong>{{ $booking->no_wa }}</strong></div>
                    <div><span>Kamar</span><strong>{{ $booking->kamar->tipe_kamar ?? '-' }}</strong><small>{{ $booking->kamar->kode_kamar ?? '' }}</small></div>
                    <div><span>Tanggal Masuk</span><strong>{{ optional($booking->tanggal_masuk)->format('d M Y') ?: '-' }}</strong></div>
                    <div class="span-2"><span>Catatan Penyewa</span><strong>{{ $booking->catatan ?: 'Tidak ada catatan.' }}</strong></div>
                    <div class="span-2"><span>Catatan Pemilik/Admin</span><strong>{{ $booking->catatan_pemilik ?: 'Belum ada catatan.' }}</strong></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="profile-detail-card h-100">
                <h3 class="mb-3">Informasi Pembayaran</h3>
                @if($booking->payment)
                    <div class="payment-status-card">
                        <span class="badge text-bg-{{ $booking->payment->status_badge }}">{{ $booking->payment->status_label }}</span>
                        <strong>{{ $booking->payment->nominal_rupiah }}</strong>
                        <small>{{ $booking->kamar->tipe_kamar ?? 'Kamar kos' }} · Jatuh tempo: {{ optional($booking->payment->jatuh_tempo)->format('d M Y') ?: '-' }}</small>
                        @if($booking->payment->bukti)
                            <a href="{{ asset('storage/' . $booking->payment->bukti) }}" target="_blank" class="btn btn-outline-primary btn-sm mt-3">Lihat Bukti Pembayaran</a>
                        @endif
                        @if($booking->payment->catatan)
                            <p class="mt-3 mb-0 text-muted">{{ $booking->payment->catatan }}</p>
                        @endif
                    </div>
                @else
                    <div class="pro-empty-state"><i class="bi bi-receipt"></i><strong>Belum ada tagihan</strong><span>Tagihan dibuat setelah booking diterima.</span></div>
                @endif
            </div>
        </div>
    </div>

    @if(auth()->user()->role === 'admin' || $booking->kos->user_id === auth()->id())
        <div class="profile-detail-card mt-4">
            <h3 class="mb-3">Update Status Booking</h3>
            <form method="POST" action="{{ route('dashboard.bookings.update', $booking) }}" class="row g-3 align-items-end">
                @csrf @method('PATCH')
                <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><option value="pending" @selected($booking->status==='pending')>Menunggu</option><option value="approved" @selected($booking->status==='approved')>Terima</option><option value="rejected" @selected($booking->status==='rejected')>Tolak</option></select></div>
                <div class="col-md-6"><label class="form-label">Catatan</label><input type="text" name="catatan_pemilik" value="{{ old('catatan_pemilik', $booking->catatan_pemilik) }}" class="form-control" placeholder="Contoh: kamar tersedia, silakan lanjut pembayaran"></div>
                <div class="col-md-2"><button class="btn btn-primary w-100" data-confirm="Update status booking ini?">Simpan</button></div>
            </form>
        </div>
    @endif
</section>
@endsection

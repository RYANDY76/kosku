@extends('layouts.app')
@section('title', 'Pembayaran Saya')
@section('content')
<section class="simple-listing-head compact-listing-head modern-listing-head">
    <div class="container">
        <span class="section-kicker">PEMBAYARAN</span>
        <h1>Pembayaran Saya</h1>
        <p>Kelola tagihan sewa, unggah bukti pembayaran, dan pantau status validasi.</p>
    </div>
</section>

<div class="container py-4">
    <div class="payment-page-grid">
        @forelse($payments as $payment)
            <article class="payment-pro-card">
                <div class="payment-pro-head">
                    <div>
                        <span>{{ $payment->kos->nama_kos ?? 'Kos' }}</span>
                        <h3>{{ $payment->nominal_rupiah }}</h3>
                        <p>{{ $payment->booking->kamar->tipe_kamar ?? 'Kamar kos' }} · Jatuh tempo: {{ optional($payment->jatuh_tempo)->format('d M Y') ?: '-' }}</p>
                    </div>
                    <span class="badge text-bg-{{ $payment->status_badge }}">{{ $payment->status_label }}</span>
                </div>
                <div class="payment-meta-grid">
                    <div><span>Kos</span><strong>{{ $payment->kos->nama_kos ?? '-' }}</strong></div>
                    <div><span>Kamar</span><strong>{{ $payment->booking->kamar->kode_kamar ?? $payment->booking->kamar->tipe_kamar ?? '-' }}</strong></div>
                    <div><span>Status</span><strong>{{ $payment->status_label }}</strong></div>
                    <div><span>Jatuh tempo</span><strong>{{ optional($payment->jatuh_tempo)->format('d M Y') ?: '-' }}</strong></div>
                </div>
                @if($payment->status === 'rejected' && $payment->catatan)
                    <div class="alert alert-danger small mb-3"><strong>Pembayaran ditolak.</strong><br>{{ $payment->catatan }}</div>
                @elseif($payment->status === 'pending')
                    <div class="alert alert-warning small mb-3">Bukti pembayaran sedang menunggu validasi pemilik/admin.</div>
                @endif
                @if($payment->status !== 'valid')
                    <form method="POST" action="{{ route('dashboard.payments.upload', $payment) }}" enctype="multipart/form-data" class="payment-dropzone-form">
                        @csrf
                        <label class="payment-dropzone">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <strong>Upload bukti pembayaran</strong>
                            <span>Format JPG, PNG, atau JPEG. Pastikan nominal dan tanggal terlihat jelas.</span>
                            <input type="file" name="bukti" class="js-payment-preview" accept="image/*" required>
                        </label>
                        <img class="payment-preview-thumb d-none" alt="Preview bukti pembayaran">
                        <button class="btn btn-primary w-100">Kirim Bukti Pembayaran</button>
                    </form>
                @else
                    <div class="payment-valid-note"><i class="bi bi-check-circle"></i> Pembayaran sudah divalidasi.</div>
                @endif
            </article>
        @empty
            <div class="content-card"><div class="empty-state enhanced"><i class="bi bi-receipt"></i><h4>Belum ada tagihan</h4><p>Tagihan akan muncul setelah pengajuan sewa diterima oleh pemilik kos.</p><a href="{{ route('kos.index') }}" class="btn btn-primary">Cari Kos</a></div></div>
        @endforelse
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Booking Saya')
@section('content')
<section class="simple-listing-head compact-listing-head modern-listing-head">
    <div class="container">
        <span class="section-kicker">BOOKING</span>
        <h1>Booking Saya</h1>
        <p>Pantau pengajuan sewa, status konfirmasi pemilik, dan lanjutkan ke pembayaran setelah booking diterima.</p>
    </div>
</section>

<div class="container py-4">
    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead><tr><th>Kos</th><th>Kamar</th><th>Tanggal Masuk</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td><strong>{{ $booking->kos->nama_kos ?? '-' }}</strong><br><small>{{ $booking->kos->lokasi_area ?? $booking->kos->alamat ?? '-' }}</small></td>
                        <td>{{ $booking->kamar->kode_kamar ?? $booking->kamar->tipe_kamar ?? '-' }}</td>
                        <td>{{ optional($booking->tanggal_masuk)->format('d M Y') ?: '-' }}</td>
                        <td><span class="badge text-bg-{{ $booking->status === 'approved' ? 'success' : ($booking->status === 'rejected' ? 'danger' : 'warning') }}">{{ $booking->status_label }}</span></td>
                        <td class="text-end d-flex gap-1 justify-content-end">
                            <a href="{{ route('dashboard.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            @if($booking->status === 'pending')
                                <form method="POST" action="{{ route('dashboard.bookings.cancel', $booking) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-danger" data-confirm="Batalkan booking ini?">Batalkan</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="bi bi-calendar2-check"></i><h4>Belum ada booking</h4><p>Pilih kos yang sesuai lalu kirim pengajuan sewa dari halaman detail kos.</p><a href="{{ route('kos.index') }}" class="btn btn-primary">Cari Kos</a></div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

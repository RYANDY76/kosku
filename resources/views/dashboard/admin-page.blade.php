@extends('layouts.dashboard')
@php
    $titles = [
        'properti' => ['Kos & Kamar', 'Kelola data kos, kamar, foto, fasilitas, harga, dan status verifikasi.'],
        'penyewa' => ['Pengguna', 'Kelola akun pencari kos dan pantau aktivitas booking.'],
        'kontrak-sewa' => ['Booking', 'Pantau dan proses pengajuan sewa dari pencari kos.'],
        'pembayaran' => ['Pembayaran', 'Validasi bukti pembayaran dan status tagihan.'],
    ];
    [$pageTitle, $pageDescription] = $titles[$page] ?? ['Panel', 'Kelola data KosKu.'];
    $panelRole = $panelRole ?? auth()->user()->role;
    $isAdminPanel = $panelRole === 'admin';
    $pageRoute = $isAdminPanel ? 'admin.page' : 'pemilik.page';
    $dashboardRoute = $isAdminPanel ? 'admin.dashboard' : 'pemilik.dashboard';
@endphp
@section('title', $pageTitle)
@section('page_title', $pageTitle)
@section('content')
<section class="admin-page-head clean-management-head">
    <div>
        <span>{{ $isAdminPanel ? 'ADMIN' : 'PEMILIK KOS' }}</span>
        <h2>{{ $pageTitle }}</h2>
        <p>{{ $pageDescription }}</p>
    </div>
    <div class="admin-page-actions">
        <a class="btn btn-light" href="{{ route($dashboardRoute) }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        @if($page === 'properti')
            <a class="btn btn-primary" href="{{ route('dashboard.kelola-kos.create') }}"><i class="bi bi-plus-circle"></i> Tambah Kos</a>
        @endif
    </div>
</section>

@if($page === 'properti')
    <div class="dashboard-card admin-page-card">
        <div class="card-header-clean">
            <div><span class="section-kicker">DATA KOS</span><h4>Daftar Kos</h4><p>{{ $isAdminPanel ? 'Admin dapat memverifikasi kos sebelum tampil di website.' : 'Pemilik hanya dapat mengelola kos miliknya sendiri.' }}</p></div>
        </div>
        <div class="table-responsive">
            <table class="table table-admin mb-0 align-middle">
                <thead><tr><th>Foto</th><th>Nama Kos</th><th>Pemilik</th><th>Harga</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($kosSaya as $item)
                    <tr>
                        <td><a href="{{ route('kos.show', $item) }}"><img class="table-cover-img" loading="lazy" src="{{ $item->foto_url }}" alt="{{ $item->nama_kos }}" onerror="this.src='{{ asset('images/default-kos.jpg') }}'"></a></td>
                        <td>
                            <a class="table-title" href="{{ route('kos.show', $item) }}">{{ $item->nama_kos }}</a><br>
                            <small>{{ $item->lokasi_area }} · {{ ucfirst($item->tipe_kos) }}</small><br>
                            <span class="badge text-bg-{{ $item->verification_status === 'approved' ? 'success' : ($item->verification_status === 'rejected' ? 'danger' : 'warning') }}">{{ $item->verification_label }}</span>
                        </td>
                        <td>{{ $item->pemilik->name ?? '-' }}</td>
                        <td><strong>{{ $item->harga_rupiah }}</strong></td>
                        <td>{{ $item->status_label }}</td>
                        <td class="text-nowrap">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('kos.show', $item) }}"><i class="bi bi-eye"></i></a>
                            <a class="btn btn-sm btn-warning" href="{{ route('dashboard.kelola-kos.edit', $item) }}"><i class="bi bi-pencil"></i></a>
                            @if($isAdminPanel)
                                <form method="POST" action="{{ route('dashboard.kelola-kos.verify', $item) }}" class="mt-2 d-flex gap-1 admin-inline-form">@csrf @method('PATCH')
                                    <select name="verification_status" class="form-select form-select-sm">
                                        <option value="pending" @selected($item->verification_status==='pending')>Pending</option>
                                        <option value="approved" @selected($item->verification_status==='approved')>Setujui</option>
                                        <option value="rejected" @selected($item->verification_status==='rejected')>Tolak</option>
                                    </select>
                                    <label class="premium-toggle"><input type="checkbox" name="premium" value="1" @checked($item->premium)> Pilihan</label>
                                    <button class="btn btn-sm btn-primary" data-confirm="Simpan status kos ini?">Simpan</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data kos.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="dashboard-card admin-page-card mt-4">
        <div class="card-header-clean"><div><span class="section-kicker">KAMAR</span><h4>Data Kamar</h4><p>Kelola unit kamar, harga, dan status ketersediaan.</p></div></div>
        <div class="table-responsive">
            <table class="table table-admin mb-0 align-middle">
                <thead><tr><th>Kamar</th><th>Kos</th><th>Harga/Bulan</th><th>Stok</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($kosSaya as $kosItem)
                    @foreach($kosItem->kamar as $kamar)
                        <tr>
                            <td><strong>{{ $kamar->kode_kamar ?: $kamar->tipe_kamar }}</strong><br><small>{{ $kamar->tipe_kamar }}</small></td>
                            <td>{{ $kosItem->nama_kos }}</td>
                            <td><strong>{{ $kamar->harga_rupiah }}</strong></td>
                            <td>{{ $kamar->jumlah_kamar }} unit</td>
                            <td><span class="badge text-bg-{{ $kamar->status === 'tersedia' ? 'success' : 'secondary' }}">{{ ucfirst($kamar->status) }}</span></td>
                            <td><a class="btn btn-sm btn-warning" href="{{ route('dashboard.kelola-kos.kamar.edit', [$kosItem, $kamar]) }}"><i class="bi bi-pencil"></i> Edit</a></td>
                        </tr>
                    @endforeach
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada kamar.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

@if($page === 'penyewa' && $isAdminPanel)
    <div class="dashboard-card admin-page-card">
        <div class="card-header-clean"><div><span class="section-kicker">PENGGUNA</span><h4>Data Pengguna</h4><p>Akun pencari kos yang terdaftar di sistem.</p></div></div>
        <div class="table-responsive"><table class="table table-admin mb-0"><thead><tr><th>Nama</th><th>Email</th><th>Booking</th><th>Pembayaran Valid</th><th>Bergabung</th></tr></thead><tbody>
        @forelse($penyewaList as $u)
            <tr><td><strong>{{ $u->name }}</strong></td><td>{{ $u->email }}</td><td>{{ $allBookings->where('user_id', $u->id)->count() }}</td><td>Rp{{ number_format($allPayments->where('user_id', $u->id)->where('status','valid')->sum('nominal'),0,',','.') }}</td><td>{{ optional($u->created_at)->format('d M Y') }}</td></tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pengguna.</td></tr>
        @endforelse
        </tbody></table></div>
    </div>
@endif

@if($page === 'kontrak-sewa')
    <div class="dashboard-card admin-page-card">
        <div class="card-header-clean"><div><span class="section-kicker">BOOKING</span><h4>Pengajuan Sewa</h4><p>Terima atau tolak booking berdasarkan ketersediaan kamar.</p></div></div>
        <div class="table-responsive"><table class="table table-admin mb-0 align-middle"><thead><tr><th>Pemesan</th><th>Kos</th><th>Kamar</th><th>Tanggal Masuk</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
        @forelse($allBookings as $booking)
            <tr>
                <td><strong>{{ $booking->nama_pemesan }}</strong><br><small>{{ $booking->user->email ?? '-' }}</small></td>
                <td>{{ $booking->kos->nama_kos ?? '-' }}</td>
                <td>{{ $booking->kamar->tipe_kamar ?? '-' }}</td>
                <td>{{ optional($booking->tanggal_masuk)->format('d M Y') ?: '-' }}</td>
                <td><span class="badge text-bg-{{ $booking->status_badge }}">{{ $booking->status_label }}</span></td>
                <td class="text-nowrap">
                    <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('dashboard.bookings.show', $booking) }}"><i class="bi bi-eye"></i> Detail</a>
                    <form method="POST" action="{{ route('dashboard.bookings.update', $booking) }}" class="d-inline-flex gap-1 admin-inline-form">@csrf @method('PATCH')
                        <select name="status" class="form-select form-select-sm"><option value="pending" @selected($booking->status==='pending')>Menunggu</option><option value="approved" @selected($booking->status==='approved')>Terima</option><option value="rejected" @selected($booking->status==='rejected')>Tolak</option></select>
                        <button class="btn btn-sm btn-primary" data-confirm="Update status booking ini?">Update</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada booking.</td></tr>
        @endforelse
        </tbody></table></div>
    </div>
@endif

@if($page === 'pembayaran')
    <div class="dashboard-card admin-page-card">
        <div class="card-header-clean"><div><span class="section-kicker">PEMBAYARAN</span><h4>Validasi Pembayaran</h4><p>Periksa bukti transfer sebelum mengubah status pembayaran.</p></div></div>
        <div class="table-responsive"><table class="table table-admin mb-0 align-middle"><thead><tr><th>Penyewa</th><th>Kos</th><th>Nominal</th><th>Jatuh Tempo</th><th>Status</th><th>Bukti</th><th>Aksi</th></tr></thead><tbody>
        @forelse($allPayments as $payment)
            <tr>
                <td><strong>{{ $payment->user->name ?? '-' }}</strong><br><small>{{ $payment->user->email ?? '-' }}</small></td>
                <td>{{ $payment->kos->nama_kos ?? '-' }}</td>
                <td><strong>{{ $payment->nominal_rupiah }}</strong></td>
                <td>{{ optional($payment->jatuh_tempo)->format('d M Y') ?: '-' }}</td>
                <td><span class="badge text-bg-{{ $payment->status_badge }}">{{ $payment->status_label }}</span></td>
                <td>@if($payment->bukti)<a target="_blank" class="btn btn-sm btn-outline-primary" href="{{ asset('storage/' . $payment->bukti) }}">Lihat</a>@else<span class="text-muted">Belum ada</span>@endif</td>
                <td><form method="POST" action="{{ route('dashboard.payments.updateStatus', $payment) }}" class="d-flex gap-1 admin-inline-form">@csrf @method('PATCH')<select name="status" class="form-select form-select-sm"><option value="unpaid" @selected($payment->status==='unpaid')>Belum Bayar</option><option value="pending" @selected($payment->status==='pending')>Menunggu</option><option value="valid" @selected($payment->status==='valid')>Valid</option><option value="rejected" @selected($payment->status==='rejected')>Tolak</option></select><input type="text" name="catatan" class="form-control form-control-sm" placeholder="Catatan" value="{{ $payment->catatan }}"><button class="btn btn-sm btn-primary" data-confirm="Simpan status pembayaran ini?">Simpan</button></form></td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pembayaran.</td></tr>
        @endforelse
        </tbody></table></div>
    </div>
@endif
@endsection

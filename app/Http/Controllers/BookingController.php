<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kos;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function show(Request $request, Booking $booking)
    {
        $booking->load(['user', 'kos.pemilik.profile', 'kamar', 'payment']);
        $user = $request->user();

        if ($user->role !== 'admin' && $booking->user_id !== $user->id && $booking->kos->user_id !== $user->id) {
            abort(403);
        }

        return view('bookings.show', compact('booking'));
    }

    public function store(Request $request, Kos $kos)
    {
        if ($request->user()->id === $kos->user_id) {
            return back()->with('error', 'Pemilik tidak dapat booking kos miliknya sendiri.');
        }

        if ($kos->status !== 'tersedia' || $kos->verification_status !== 'approved') {
            return back()->with('error', 'Kos ini belum tersedia untuk pengajuan sewa.');
        }

        $data = $request->validate([
            'kamar_id'      => ['nullable', Rule::exists('kamars', 'id')->where('kos_id', $kos->id)],
            'nama_pemesan'  => ['required', 'string', 'max:120'],
            'no_wa'         => ['required', 'regex:/^[0-9]{10,15}$/'],
            'tanggal_masuk' => ['nullable', 'date', 'after_or_equal:today'],
            'catatan'       => ['nullable', 'string', 'max:1000'],
        ], [
            'no_wa.regex'              => 'Nomor WhatsApp harus berupa angka, contoh: 6281234567890.',
            'tanggal_masuk.after_or_equal' => 'Tanggal masuk tidak boleh tanggal yang sudah lewat.',
        ]);

        if (! empty($data['kamar_id'])) {
            $kamar = $kos->kamar()->where('id', $data['kamar_id'])->first();
            if (! $kamar || $kamar->status !== 'tersedia' || $kamar->jumlah_kamar <= 0) {
                return back()->with('error', 'Kamar yang dipilih sedang penuh atau tidak tersedia.');
            }
        }

        $activeBooking = Booking::where('user_id', $request->user()->id)
            ->where('kos_id', $kos->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($activeBooking) {
            return back()->with('error', 'Kamu sudah memiliki pengajuan aktif untuk kos ini.');
        }

        $data['user_id'] = $request->user()->id;
        $data['kos_id']  = $kos->id;
        $data['status']  = 'pending';
        Booking::create($data);

        return back()->with('success', 'Pengajuan sewa berhasil dikirim. Pemilik kos akan segera mengkonfirmasi.');
    }

    // User hanya dapat membatalkan booking miliknya yang masih menunggu konfirmasi.
    public function cancel(Request $request, Booking $booking)
    {
        $user = $request->user();

        if ($booking->user_id !== $user->id) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Hanya booking dengan status menunggu yang bisa dibatalkan.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    public function update(Request $request, Booking $booking)
    {
        $user = $request->user();
        if ($user->role !== 'admin' && $booking->kos->user_id !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'status'          => ['required', 'in:pending,approved,rejected,cancelled'],
            'catatan_pemilik' => ['nullable', 'string', 'max:1000'],
        ]);

        $previousStatus = $booking->status;

        if ($data['status'] === 'approved' && $previousStatus !== 'approved' && $booking->kamar) {
            $booking->kamar->refresh();
            if ($booking->kamar->status !== 'tersedia' || $booking->kamar->jumlah_kamar <= 0) {
                return back()->with('error', 'Kamar yang dipilih sudah penuh atau tidak tersedia.');
            }
        }

        $booking->update($data);
        if ($data['status'] === 'approved' && $previousStatus !== 'approved') {
            if ($booking->kamar) {
                $booking->kamar->decrement('jumlah_kamar');
                $booking->kamar->refresh();
                if ($booking->kamar->jumlah_kamar <= 0) {
                    $booking->kamar->update(['status' => 'penuh', 'jumlah_kamar' => 0]);
                }
            }

            $nominal = optional($booking->kamar)->harga ?? $booking->kos->harga;
            Payment::firstOrCreate(
                ['booking_id' => $booking->id],
                [
                    'user_id'     => $booking->user_id,
                    'kos_id'      => $booking->kos_id,
                    'nominal'     => $nominal,
                    'metode'      => 'Transfer Bank',
                    'status'      => 'unpaid',
                    'jatuh_tempo' => now()->addDays(7),
                ]
            );
        }

        if ($previousStatus === 'approved' && $data['status'] !== 'approved' && $booking->kamar) {
            $booking->kamar->increment('jumlah_kamar');
            $booking->kamar->update(['status' => 'tersedia']);
        }

        return back()->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}

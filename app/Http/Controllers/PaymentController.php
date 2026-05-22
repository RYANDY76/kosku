<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function upload(Request $request, Payment $payment)
    {
        if ($request->user()->role !== 'admin' && $payment->user_id !== $request->user()->id) {
            abort(403);
        }

        $data = $request->validate([
            'bukti' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        if ($payment->bukti && ! str_starts_with($payment->bukti, 'images/')) {
            Storage::disk('public')->delete($payment->bukti);
        }

        $payment->update([
            'bukti'    => $request->file('bukti')->store('payments', 'public'),
            'status'   => 'pending',
            'catatan'  => $data['catatan'] ?? $payment->catatan,
            'paid_at'  => now(),
        ]);
        return back()->with('success', 'Bukti pembayaran berhasil diunggah dan menunggu validasi.');
    }

    public function updateStatus(Request $request, Payment $payment)
    {
        $user = $request->user();
        if ($user->role !== 'admin' && $payment->kos->user_id !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'status' => ['required', 'in:unpaid,pending,valid,rejected'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        if ($data['status'] === 'rejected' && empty($data['catatan'])) {
            return back()->withErrors(['catatan' => 'Catatan wajib diisi saat pembayaran ditolak.'])->withInput();
        }

        $payment->update([
            'status'  => $data['status'],
            'catatan' => $data['catatan'] ?? $payment->catatan,
            'paid_at' => $data['status'] === 'valid' ? now() : $payment->paid_at,
        ]);
        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}

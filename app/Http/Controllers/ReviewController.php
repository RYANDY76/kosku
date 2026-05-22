<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kos;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Kos $kos)
    {
        // Hanya user yang pernah booking approved di kos ini yang bisa review
        $hasApprovedBooking = Booking::where('user_id', $request->user()->id)
            ->where('kos_id', $kos->id)
            ->where('status', 'approved')
            ->exists();

        if (! $hasApprovedBooking) {
            return back()->with('error', 'Kamu hanya bisa memberikan review setelah booking kamu diterima di kos ini.');
        }

        $data = $request->validate([
            'rating'   => ['required', 'integer', 'min:1', 'max:5'],
            'komentar' => ['required', 'string', 'min:8', 'max:1000'],
        ]);

        Review::updateOrCreate(
            ['user_id' => $request->user()->id, 'kos_id' => $kos->id],
            ['rating' => $data['rating'], 'komentar' => $data['komentar']]
        );

        return back()->with('success', 'Review berhasil disimpan.');
    }

    public function destroy(Review $review)
    {
        $user = request()->user();
        if ($user->role !== 'admin' && $review->user_id !== $user->id) {
            abort(403);
        }
        $review->delete();
        return back()->with('success', 'Review berhasil dihapus.');
    }
}

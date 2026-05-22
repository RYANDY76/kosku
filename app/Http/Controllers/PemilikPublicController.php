<?php

namespace App\Http\Controllers;

use App\Models\User;

class PemilikPublicController extends Controller
{
    public function show(User $user)
    {
        if ($user->role !== 'pemilik') {
            abort(404);
        }

        $user->load(['profile', 'kos' => function ($query) {
            $query->with(['fasilitas', 'reviews'])
                ->withAvg('reviews', 'rating')
                ->where('verification_status', 'approved')
                ->latest();
        }]);

        return view('pemilik.show', ['pemilik' => $user]);
    }
}

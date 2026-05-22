<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\Kamar;

class HomeController extends Controller
{
    public function index()
    {
        $premiumKos = Kos::with('fasilitas')->withAvg('reviews', 'rating')->where('verification_status', 'approved')->where('premium', true)->latest()->take(6)->get();
        $latestKos = Kos::with('fasilitas')->withAvg('reviews', 'rating')->where('verification_status', 'approved')->latest()->take(6)->get();
        $areas = Kos::where('verification_status', 'approved')
            ->whereNotNull('lokasi_area')
            ->distinct()
            ->orderBy('lokasi_area')
            ->pluck('lokasi_area')
            ->values();

        $totalKos = Kos::where('verification_status', 'approved')->count();
        $totalKamarTersedia = Kamar::where('status', 'tersedia')
            ->whereHas('kos', fn ($query) => $query->where('verification_status', 'approved'))
            ->sum('jumlah_kamar');
        $areaCount = $areas->count();

        return view('home', compact('premiumKos', 'latestKos', 'areas', 'totalKos', 'totalKamarTersedia', 'areaCount'));
    }
}

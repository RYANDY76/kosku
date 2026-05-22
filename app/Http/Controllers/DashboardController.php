<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Fasilitas;
use App\Models\Kamar;
use App\Models\Kos;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'user') {
            return view('dashboard.index', [
                'bookingSaya' => $user->bookings()->with(['kos', 'kamar', 'payment'])->latest()->get(),
                'paymentsSaya' => $user->payments()->with(['booking', 'kos'])->latest()->get(),
                'rekomendasi' => Kos::with(['fasilitas', 'reviews'])->withAvg('reviews', 'rating')->where('verification_status', 'approved')->where('status', 'tersedia')->orderByDesc('premium')->latest()->take(6)->get(),
            ]);
        }

        return view('dashboard.index', $this->adminData($user));
    }

    public function adminPage(string $section, string $page)
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        return $this->renderManagementPage($section, $page, 'admin');
    }

    public function ownerPage(string $section, string $page)
    {
        abort_unless(auth()->user()->role === 'pemilik', 403);
        return $this->renderManagementPage($section, $page, 'pemilik');
    }

    private function renderManagementPage(string $section, string $page, string $role)
    {
        $allowed = $role === 'admin'
            ? [
                'master-data' => ['properti', 'penyewa'],
                'transaksi' => ['kontrak-sewa', 'pembayaran'],
            ]
            : [
                'master-data' => ['properti'],
                'transaksi' => ['kontrak-sewa', 'pembayaran'],
            ];

        if (! isset($allowed[$section]) || ! in_array($page, $allowed[$section], true)) {
            abort(404);
        }

        return view('dashboard.admin-page', $this->adminData(auth()->user()) + [
            'section' => $section,
            'page' => $page,
            'panelRole' => $role,
        ]);
    }

    private function adminData(User $user): array
    {
        $kosQuery = Kos::query();

        if ($user->role === 'pemilik') {
            $kosQuery->where('user_id', $user->id);
        }

        $kosIds = (clone $kosQuery)->pluck('id');
        $kosSaya = (clone $kosQuery)
            ->with(['fasilitas', 'kamar', 'pemilik', 'reviews', 'fotos'])
            ->withCount(['bookings', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->get();

        $bookingQuery = Booking::with(['kos', 'user', 'kamar', 'payment'])
            ->whereIn('kos_id', $kosIds)
            ->latest();

        $paymentQuery = Payment::with(['booking.kamar', 'user', 'kos'])
            ->whereIn('kos_id', $kosIds)
            ->latest();

        $payments = (clone $paymentQuery)->take(20)->get();
        $bookings = (clone $bookingQuery)->take(20)->get();
        $allBookings = (clone $bookingQuery)->get();
        $allPayments = (clone $paymentQuery)->get();

        return [
            'totalKos' => (clone $kosQuery)->count(),
            'kosPending' => (clone $kosQuery)->where('verification_status', 'pending')->count(),
            'totalFasilitas' => Fasilitas::count(),
            'totalPemilik' => User::where('role', 'pemilik')->count(),
            'totalUser' => User::count(),
            'totalBooking' => $allBookings->count(),
            'bookingPending' => $allBookings->where('status', 'pending')->count(),
            'kamarTersedia' => Kamar::whereIn('kos_id', $kosIds)->where('status', 'tersedia')->sum('jumlah_kamar'),
            'kamarPenuh' => Kamar::whereIn('kos_id', $kosIds)->where('status', 'penuh')->sum('jumlah_kamar'),
            'totalKamar' => Kamar::whereIn('kos_id', $kosIds)->sum('jumlah_kamar'),
            'kosSaya' => $kosSaya,
            'bookings' => $bookings,
            'allBookings' => $allBookings,
            'allBookingCount' => $allBookings->count(),
            'payments' => $payments,
            'allPayments' => $allPayments,
            'paymentValid' => $allPayments->where('status', 'valid')->sum('nominal'),
            'paymentPending' => $allPayments->where('status', 'pending')->sum('nominal'),
            'paymentUnpaid' => $allPayments->where('status', 'unpaid')->sum('nominal'),
            'users' => $user->role === 'admin' ? User::latest()->get() : collect(),
            'pemilikList' => User::where('role', 'pemilik')->with('profile')->latest()->get(),
            'penyewaList' => User::where('role', 'user')->latest()->get(),
            'fasilitasList' => Fasilitas::orderBy('nama_fasilitas')->get(),
            'reviews' => Review::with(['user', 'kos'])
                ->when($user->role === 'pemilik', fn ($q) => $q->whereIn('kos_id', $kosIds))
                ->latest()
                ->take(30)
                ->get(),
            'rekomendasi' => Kos::with('fasilitas')->where('verification_status', 'approved')->latest()->take(4)->get(),
        ];
    }

    public function myBookings()
    {
        $user = Auth::user();
        abort_unless($user->role === 'user', 403);

        return view('dashboard.user-bookings', [
            'bookings' => $user->bookings()->with(['kos', 'kamar', 'payment'])->latest()->get(),
        ]);
    }

    public function myPayments()
    {
        $user = Auth::user();
        abort_unless($user->role === 'user', 403);

        return view('dashboard.user-payments', [
            'payments' => $user->payments()->with(['booking.kamar', 'kos'])->latest()->get(),
        ]);
    }
}

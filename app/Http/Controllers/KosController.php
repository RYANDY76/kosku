<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KosController extends Controller
{
    public function index(Request $request)
    {
        $fasilitasList = Fasilitas::orderBy('nama_fasilitas')->get();
        $areaList = Kos::select('lokasi_area')->where('verification_status', 'approved')->whereNotNull('lokasi_area')->distinct()->orderBy('lokasi_area')->pluck('lokasi_area');
        $priceMin = 0;
        $priceStep = 50000;
        $priceMax = (int) Kos::where('verification_status', 'approved')->max('harga');
        $priceMax = max(3000000, (int) ceil(max(1, $priceMax) / 100000) * 100000);
        $parseMoney = fn ($value) => (int) preg_replace('/[^0-9]/', '', (string) $value);
        $selectedHargaMin = max(0, $parseMoney($request->input('harga_min', 0)));
        $selectedHargaMaks = $request->filled('harga_maks') ? $parseMoney($request->input('harga_maks')) : $priceMax;
        if ($selectedHargaMaks <= 0) {
            $selectedHargaMaks = $priceMax;
        }
        $selectedHargaMin = min($selectedHargaMin, max(0, $priceMax - $priceStep));
        $selectedHargaMaks = max($selectedHargaMin + $priceStep, min($priceMax, $selectedHargaMaks));

        $kos = Kos::with(['fasilitas', 'kamar', 'reviews', 'fotos'])
            ->withAvg('reviews', 'rating')
            ->withCount(['reviews', 'kamar as kamar_tersedia_count' => function ($query) {
                $query->where('status', 'tersedia');
            }])
            ->where('verification_status', 'approved')
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = $request->q;
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama_kos', 'like', "%{$keyword}%")
                        ->orWhere('alamat', 'like', "%{$keyword}%")
                        ->orWhere('lokasi_area', 'like', "%{$keyword}%");
                });
            })
            ->when($request->filled('area'), fn ($query) => $query->where('lokasi_area', 'like', '%' . $request->area . '%'))
            ->when($selectedHargaMin > 0, fn ($query) => $query->where('harga', '>=', $selectedHargaMin))
            ->when($request->filled('harga_maks') && $selectedHargaMaks < $priceMax, fn ($query) => $query->where('harga', '<=', $selectedHargaMaks))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('tipe_kos'), fn ($query) => $query->where('tipe_kos', $request->tipe_kos))
            ->when($request->filled('jarak_maks'), fn ($query) => $query->whereNotNull('jarak_kampus')->where('jarak_kampus', '<=', $request->jarak_maks))
            ->when($request->filled('fasilitas'), function ($query) use ($request) {
                $selectedFasilitas = collect((array) $request->input('fasilitas'))->filter();
                foreach ($selectedFasilitas as $fasilitasId) {
                    $query->whereHas('fasilitas', fn ($q) => $q->where('fasilitas.id', $fasilitasId));
                }
            })
            ->when($request->boolean('premium'), fn ($query) => $query->where('premium', true))
            ->when($request->input('sort') === 'cheap', fn ($query) => $query->orderBy('harga', 'asc'))
            ->when($request->input('sort') === 'expensive', fn ($query) => $query->orderBy('harga', 'desc'))
            ->when($request->input('sort') === 'rating', fn ($query) => $query->orderByDesc('reviews_avg_rating'))
            ->when($request->input('sort') === 'premium', fn ($query) => $query->orderByDesc('premium')->latest())
            ->when(! in_array($request->input('sort'), ['cheap', 'expensive', 'premium', 'rating']), fn ($query) => $query->latest())
            ->paginate(9)
            ->withQueryString();

        return view('kos.index', compact('kos', 'fasilitasList', 'areaList', 'priceMin', 'priceMax', 'selectedHargaMin', 'selectedHargaMaks'));
    }

    public function create()
    {
        $fasilitas = Fasilitas::orderBy('nama_fasilitas')->get();
        return view('kos.create', compact('fasilitas'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['user_id'] = $request->user()->id;
        $data['premium'] = $request->user()->role === 'admin' ? $request->boolean('premium') : false;
        $data['verification_status'] = $request->user()->role === 'admin' ? $request->input('verification_status', 'approved') : 'pending';

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('kos', 'public');
        }

        $kos = Kos::create($data);
        $kos->fasilitas()->sync($request->input('fasilitas', []));
        $this->syncDetailPhotos($request, $kos);

        return redirect()->route('dashboard.index')->with('success', 'Data kos berhasil ditambahkan. Kos pemilik akan tampil setelah diverifikasi admin.');
    }

    public function show(Kos $kos)
    {
        if ($kos->verification_status !== 'approved' && (! auth()->check() || (auth()->user()->role !== 'admin' && auth()->id() !== $kos->user_id))) {
            abort(404);
        }
        $kos->load(['fasilitas', 'kamar', 'pemilik.profile', 'fotos']);

        $latestReviews = $kos->reviews()
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        $reviewCount = $kos->reviews()->count();
        $ratingAverage = round((float) $kos->reviews()->avg('rating'), 1);
        $userReview = auth()->check() ? $kos->reviews()->where('user_id', auth()->id())->first() : null;

        return view('kos.show', compact('kos', 'userReview', 'latestReviews', 'reviewCount', 'ratingAverage'));
    }

    public function edit(Kos $kos)
    {
        $this->authorizeKosOwner($kos);
        $fasilitas = Fasilitas::orderBy('nama_fasilitas')->get();
        $kos->load(['fasilitas', 'fotos']);
        return view('kos.edit', compact('kos', 'fasilitas'));
    }

    public function update(Request $request, Kos $kos)
    {
        $this->authorizeKosOwner($kos);
        $data = $this->validatedData($request);
        $data['premium'] = $request->user()->role === 'admin' ? $request->boolean('premium') : $kos->premium;
        $data['verification_status'] = $request->user()->role === 'admin'
            ? $request->input('verification_status', $kos->verification_status)
            : 'pending';

        if ($request->hasFile('foto')) {
            if ($kos->foto && ! str_starts_with($kos->foto, 'images/')) {
                Storage::disk('public')->delete($kos->foto);
            }
            $data['foto'] = $request->file('foto')->store('kos', 'public');
        }

        $kos->update($data);
        $kos->fasilitas()->sync($request->input('fasilitas', []));
        $this->syncDetailPhotos($request, $kos);

        return redirect()->route('dashboard.index')->with('success', 'Data kos berhasil diperbarui.');
    }

    public function destroy(Kos $kos)
    {
        $this->authorizeKosOwner($kos);
        if ($kos->foto && ! str_starts_with($kos->foto, 'images/')) {
            Storage::disk('public')->delete($kos->foto);
        }
        foreach ($kos->fotos as $foto) {
            if (! str_starts_with($foto->path, 'images/')) {
                Storage::disk('public')->delete($foto->path);
            }
        }
        $kos->delete();
        return redirect()->route('dashboard.index')->with('success', 'Data kos berhasil dihapus.');
    }

    public function verify(Request $request, Kos $kos)
    {
        $data = $request->validate([
            'verification_status' => ['required', 'in:pending,approved,rejected'],
            'premium' => ['nullable'],
        ]);
        $kos->update([
            'verification_status' => $data['verification_status'],
            'premium' => $request->boolean('premium'),
        ]);
        return back()->with('success', 'Status verifikasi kos berhasil diperbarui.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'nama_kos' => ['required', 'string', 'max:150'],
            'tipe_kos' => ['required', 'in:putra,putri,campur'],
            'alamat' => ['required', 'string'],
            'lokasi_area' => ['nullable', 'string', 'max:100'],
            'jarak_kampus' => ['nullable', 'numeric', 'min:0', 'max:99'],
            'deskripsi' => ['required', 'string', 'min:15'],
            'harga' => ['required', 'integer', 'min:0'],
            'no_wa' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'status' => ['required', 'in:tersedia,penuh'],
            'premium' => ['nullable'],
            'verification_status' => ['nullable', 'in:pending,approved,rejected'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'foto_kamar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'foto_dapur' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'foto_parkiran' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'fasilitas' => ['array'],
            'fasilitas.*' => ['exists:fasilitas,id'],
        ], [
            'no_wa.regex' => 'Nomor WhatsApp harus berupa angka, contoh: 6281234567890.',
            'deskripsi.min' => 'Deskripsi minimal 15 karakter agar calon penyewa mendapat informasi yang jelas.',
        ]);
    }

    private function syncDetailPhotos(Request $request, Kos $kos): void
    {
        $slots = [
            'foto_kamar' => ['jenis' => 'kamar', 'caption' => 'Foto Kamar', 'urutan' => 2],
            'foto_dapur' => ['jenis' => 'dapur', 'caption' => 'Foto Dapur', 'urutan' => 3],
            'foto_parkiran' => ['jenis' => 'parkiran', 'caption' => 'Foto Parkiran', 'urutan' => 4],
        ];

        foreach ($slots as $field => $config) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $existing = $kos->fotos()->where('jenis', $config['jenis'])->first();
            if ($existing && ! str_starts_with($existing->path, 'images/')) {
                Storage::disk('public')->delete($existing->path);
            }

            $path = $request->file($field)->store('kos/gallery', 'public');
            $kos->fotos()->updateOrCreate(
                ['jenis' => $config['jenis']],
                ['path' => $path, 'caption' => $config['caption'], 'urutan' => $config['urutan']]
            );
        }
    }

    private function authorizeKosOwner(Kos $kos): void
    {
        $user = request()->user();
        if ($user->role !== 'admin' && $kos->user_id !== $user->id) {
            abort(403, 'Anda hanya dapat mengelola kos milik sendiri.');
        }
    }
}

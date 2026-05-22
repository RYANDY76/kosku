<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KamarController extends Controller
{
    public function create(Kos $kos)
    {
        $this->authorizeKosOwner($kos);
        return view('kamar.create', compact('kos'));
    }

    public function store(Request $request, Kos $kos)
    {
        $this->authorizeKosOwner($kos);
        $data = $this->validatedData($request);
        if ($request->hasFile('foto_file')) {
            $data['foto'] = $request->file('foto_file')->store('kamar', 'public');
        }
        unset($data['foto_file']);
        $kos->kamar()->create($data);
        return redirect()->route('kos.show', $kos)->with('success', 'Data kamar berhasil ditambahkan.');
    }

    public function edit(Kos $kos, Kamar $kamar)
    {
        $this->authorizeKosOwner($kos);
        $this->ensureKamarBelongsToKos($kos, $kamar);
        return view('kamar.edit', compact('kos', 'kamar'));
    }

    public function update(Request $request, Kos $kos, Kamar $kamar)
    {
        $this->authorizeKosOwner($kos);
        $this->ensureKamarBelongsToKos($kos, $kamar);
        $data = $this->validatedData($request);
        if ($request->hasFile('foto_file')) {
            if ($kamar->foto && ! str_starts_with($kamar->foto, 'images/')) {
                Storage::disk('public')->delete($kamar->foto);
            }
            $data['foto'] = $request->file('foto_file')->store('kamar', 'public');
        }
        unset($data['foto_file']);
        $kamar->update($data);
        return redirect()->route('kos.show', $kos)->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy(Kos $kos, Kamar $kamar)
    {
        $this->authorizeKosOwner($kos);
        $this->ensureKamarBelongsToKos($kos, $kamar);
        $kamar->delete();
        return redirect()->route('kos.show', $kos)->with('success', 'Data kamar berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'tipe_kamar' => ['required', 'string', 'max:100'],
            'kode_kamar' => ['nullable', 'string', 'max:40'],
            'lantai' => ['nullable', 'integer', 'min:1', 'max:99'],
            'luas_kamar' => ['nullable', 'numeric', 'min:0', 'max:999'],
            'harga' => ['required', 'integer', 'min:0'],
            'harga_harian' => ['nullable', 'integer', 'min:0'],
            'harga_tahunan' => ['nullable', 'integer', 'min:0'],
            'foto' => ['nullable', 'string', 'max:255'],
            'foto_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'jumlah_kamar' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:tersedia,penuh'],
            'catatan' => ['nullable', 'string'],
        ]);
    }

    private function authorizeKosOwner(Kos $kos): void
    {
        $user = request()->user();
        if ($user->role !== 'admin' && $kos->user_id !== $user->id) {
            abort(403, 'Anda hanya dapat mengelola kos milik sendiri.');
        }
    }

    private function ensureKamarBelongsToKos(Kos $kos, Kamar $kamar): void
    {
        if ((int) $kamar->kos_id !== (int) $kos->id) {
            abort(404);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    public function index()
    {
        $fasilitas = Fasilitas::orderBy('nama_fasilitas')->paginate(10);
        return view('fasilitas.index', compact('fasilitas'));
    }

    public function create()
    {
        return view('fasilitas.create');
    }

    public function store(Request $request)
    {
        Fasilitas::create($request->validate([
            'nama_fasilitas' => ['required', 'string', 'max:100', 'unique:fasilitas,nama_fasilitas'],
        ]));

        return redirect()->route('dashboard.fasilitas.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function edit(Fasilitas $fasilita)
    {
        return view('fasilitas.edit', ['fasilitas' => $fasilita]);
    }

    public function update(Request $request, Fasilitas $fasilita)
    {
        $fasilita->update($request->validate([
            'nama_fasilitas' => ['required', 'string', 'max:100', 'unique:fasilitas,nama_fasilitas,'.$fasilita->id],
        ]));

        return redirect()->route('dashboard.fasilitas.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Fasilitas $fasilita)
    {
        $fasilita->delete();
        return redirect()->route('dashboard.fasilitas.index')->with('success', 'Fasilitas berhasil dihapus.');
    }
}

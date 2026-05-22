<?php

namespace App\Http\Controllers;

use App\Models\ProfilePemilik;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load('profile');
        return view('profile.show', compact('user'));
    }

    public function edit(Request $request)
    {
        $user = $request->user()->load('profile');
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', 'unique:users,email,' . $user->id],
            'no_wa' => ['nullable', 'regex:/^[0-9]{10,15}$/'],
            'alamat' => ['nullable', 'string', 'max:1000'],
        ], ['no_wa.regex' => 'Nomor WhatsApp harus berupa angka, contoh: 6281234567890.']);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if ($user->role === 'pemilik') {
            ProfilePemilik::updateOrCreate(
                ['user_id' => $user->id],
                ['no_wa' => $data['no_wa'] ?? null, 'alamat' => $data['alamat'] ?? null]
            );
        }

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}

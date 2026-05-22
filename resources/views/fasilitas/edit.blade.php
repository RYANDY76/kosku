@extends('layouts.dashboard')
@section('title', 'Edit Fasilitas')
@section('content')
<div><span class="section-label">Admin</span><h1 class="fw-black mb-4">Edit Fasilitas</h1><div class="card border-0 shadow-sm form-card"><div class="card-body p-4"><form method="POST" action="{{ route('dashboard.fasilitas.update', $fasilitas) }}">@csrf @method('PUT')<label class="form-label">Nama Fasilitas</label><input name="nama_fasilitas" value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas) }}" class="form-control mb-3" required><button class="btn btn-primary">Update</button><a href="{{ route('dashboard.fasilitas.index') }}" class="btn btn-outline-secondary">Batal</a></form></div></div></div>
@endsection

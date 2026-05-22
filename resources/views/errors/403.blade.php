@extends('layouts.app')
@section('title', 'Akses Ditolak')
@section('content')
<div class="container py-5">
    <div class="empty-state py-5">
        <i class="bi bi-shield-lock display-4 text-danger"></i>
        <h1 class="fw-black mt-3">403 - Akses Ditolak</h1>
        <p class="text-muted">Kamu tidak punya hak akses untuk membuka halaman ini.</p>
        <a href="{{ route('dashboard.index') }}" class="btn btn-primary">Kembali ke Dashboard</a>
    </div>
</div>
@endsection

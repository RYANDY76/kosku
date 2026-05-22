@extends('layouts.dashboard')
@section('title', 'Tambah Kamar')
@section('page_title', 'Tambah Kamar')
@section('content')
<div class="dashboard-card p-4"><h4 class="fw-bold mb-1">Tambah Kamar - {{ $kos->nama_kos }}</h4><p class="text-muted">Lengkapi tipe kamar, harga, jumlah, dan status kamar.</p><form method="POST" enctype="multipart/form-data" action="{{ route('dashboard.kelola-kos.kamar.store', $kos) }}">@include('kamar._form')</form></div>
@endsection

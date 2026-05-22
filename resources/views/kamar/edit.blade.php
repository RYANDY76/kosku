@extends('layouts.dashboard')
@section('title', 'Edit Kamar')
@section('page_title', 'Edit Kamar')
@section('content')
<div class="dashboard-card p-4"><h4 class="fw-bold mb-1">Edit Kamar - {{ $kos->nama_kos }}</h4><p class="text-muted">Perbarui data kamar kos.</p><form method="POST" enctype="multipart/form-data" action="{{ route('dashboard.kelola-kos.kamar.update', [$kos, $kamar]) }}">@method('PUT') @include('kamar._form')</form></div>
@endsection

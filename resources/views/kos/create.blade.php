@extends('layouts.dashboard')
@section('title', 'Tambah Kos')
@section('page_title', 'Tambah Kos Baru')
@section('content')
<form method="POST" action="{{ route('dashboard.kelola-kos.store') }}" enctype="multipart/form-data">
    @include('kos._form')
</form>
@endsection

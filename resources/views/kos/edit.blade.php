@extends('layouts.dashboard')
@section('title', 'Edit Kos')
@section('page_title', 'Edit Kos')
@section('content')
<form method="POST" action="{{ route('dashboard.kelola-kos.update', $kos) }}" enctype="multipart/form-data">
    @method('PUT')
    @include('kos._form')
</form>
@endsection

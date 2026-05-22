@extends('layouts.dashboard')
@section('title', 'Kelola Fasilitas')
@section('content')
<div>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4"><div><span class="section-label">Admin</span><h1 class="fw-black mb-0">Kelola Fasilitas</h1></div><a href="{{ route('dashboard.fasilitas.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>Tambah Fasilitas</a></div>
    <div class="card border-0 shadow-sm"><div class="card-body p-4"><div class="table-responsive"><table class="table table-hover align-middle"><thead class="table-light"><tr><th>Nama Fasilitas</th><th>Aksi</th></tr></thead><tbody>@foreach($fasilitas as $item)<tr><td class="fw-semibold"><i class="bi bi-stars text-primary me-2"></i>{{ $item->nama_fasilitas }}</td><td><a href="{{ route('dashboard.fasilitas.edit', $item) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a><form class="d-inline" method="POST" action="{{ route('dashboard.fasilitas.destroy', $item) }}" onsubmit="return confirm('Hapus fasilitas ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form></td></tr>@endforeach</tbody></table></div>{{ $fasilitas->links('pagination::bootstrap-5') }}</div></div>
</div>
@endsection

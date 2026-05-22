@extends('layouts.dashboard')
@section('title', 'Kelola User')
@section('content')
<div>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
        <div><span class="section-label">Admin</span><h1 class="fw-black mb-0">Kelola User</h1></div>
        <a href="{{ route('dashboard.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>
    <form class="filter-card mb-4" method="GET">
        <div class="row g-3">
            <div class="col-md-7"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama atau email"></div>
            <div class="col-md-3"><select name="role" class="form-select"><option value="">Semua Role</option><option value="admin" @selected(request('role')==='admin')>Admin</option><option value="pemilik" @selected(request('role')==='pemilik')>Pemilik</option><option value="user" @selected(request('role')==='user')>User</option></select></div>
            <div class="col-md-2 d-grid"><button class="btn btn-primary">Cari</button></div>
        </div>
    </form>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>Nama</th><th>Email</th><th>Role</th><th>Dibuat</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="fw-semibold">{{ $user->name }}</td><td>{{ $user->email }}</td>
                            <td><span class="badge text-bg-light border">{{ ucfirst($user->role) }}</span></td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td class="text-nowrap">
                                <form method="POST" action="{{ route('dashboard.users.update', $user) }}" class="d-inline-flex gap-2 align-items-center">
                                    @csrf @method('PATCH')
                                    <select name="role" class="form-select form-select-sm" style="width: 120px">
                                        <option value="admin" @selected($user->role==='admin')>Admin</option>
                                        <option value="pemilik" @selected($user->role==='pemilik')>Pemilik</option>
                                        <option value="user" @selected($user->role==='user')>User</option>
                                    </select>
                                    <button class="btn btn-sm btn-primary">Update</button>
                                </form>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('dashboard.users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $users->links('pagination::bootstrap-5') }}</div>
    </div>
</div>
@endsection

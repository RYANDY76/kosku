@extends('layouts.app')
@section('title', 'Cari Kos')
@section('content')
@php
    $selectedFacilities = collect((array) request('fasilitas'))->filter()->map(fn($v) => (string) $v)->values()->all();
    $sortLabels = ['latest'=>'Terbaru','cheap'=>'Termurah','rating'=>'Rating tertinggi','premium'=>'Rekomendasi'];
    $activeSort = request('sort', 'latest');
    $budgetMax = request()->filled('harga_maks') ? (int) preg_replace('/[^0-9]/', '', request('harga_maks')) : $selectedHargaMaks;
    $budgetMax = max($priceMin, min($priceMax, $budgetMax ?: $priceMax));
@endphp

<section class="simple-listing-head compact-listing-head modern-listing-head">
    <div class="container">
        <span class="section-kicker">CARI KOS</span>
        <h1>Temukan kos sesuai kebutuhan Anda</h1>
        <p>Gunakan pencarian singkat untuk melihat pilihan kos berdasarkan area, budget, tipe, dan ketersediaan kamar.</p>
    </div>
</section>

<div class="container clean-listing-page py-4">
    <form class="modern-filter-bar" action="{{ route('kos.index') }}">
        <div class="modern-filter-main">
            <label class="modern-filter-field wide">
                <span>Nama / lokasi</span>
                <div>
                    <i class="bi bi-search"></i>
                    <input name="q" value="{{ request('q') }}" placeholder="Contoh: Palu Timur, Kos Melati">
                </div>
            </label>

            <label class="modern-filter-field">
                <span>Area</span>
                <select name="area">
                    <option value="">Semua area</option>
                    @foreach($areaList as $area)
                        <option value="{{ $area }}" @selected(request('area')===$area)>{{ $area }}</option>
                    @endforeach
                </select>
            </label>

            <label class="modern-filter-field">
                <span>Tipe</span>
                <select name="tipe_kos">
                    <option value="">Semua tipe</option>
                    <option value="putra" @selected(request('tipe_kos')==='putra')>Putra</option>
                    <option value="putri" @selected(request('tipe_kos')==='putri')>Putri</option>
                    <option value="campur" @selected(request('tipe_kos')==='campur')>Campur</option>
                </select>
            </label>

            <label class="modern-filter-field">
                <span>Budget maksimal</span>
                <input class="js-money-field" name="harga_maks" value="{{ $budgetMax ? 'Rp ' . number_format($budgetMax, 0, ',', '.') : '' }}" inputmode="numeric" placeholder="Rp 1.500.000">
            </label>

            <label class="modern-filter-field compact">
                <span>Status</span>
                <select name="status">
                    <option value="">Semua</option>
                    <option value="tersedia" @selected(request('status')==='tersedia')>Tersedia</option>
                    <option value="penuh" @selected(request('status')==='penuh')>Penuh</option>
                </select>
            </label>

            <button class="btn btn-primary modern-filter-submit"><i class="bi bi-search me-1"></i> Cari</button>
        </div>

        <div class="modern-filter-secondary">
            <button class="btn btn-link p-0" type="button" data-bs-toggle="collapse" data-bs-target="#facilityFilter" aria-expanded="{{ count($selectedFacilities) ? 'true' : 'false' }}">
                <i class="bi bi-sliders me-1"></i> Fasilitas {{ count($selectedFacilities) ? '(' . count($selectedFacilities) . ')' : '' }}
            </button>
            <a href="{{ route('kos.index') }}">Reset filter</a>
        </div>

        <div class="collapse {{ count($selectedFacilities) ? 'show' : '' }}" id="facilityFilter">
            <div class="modern-facility-filter">
                @foreach($fasilitasList->take(8) as $fas)
                    <label><input type="checkbox" name="fasilitas[]" value="{{ $fas->id }}" @checked(in_array((string)$fas->id, $selectedFacilities))><span>{{ $fas->nama_fasilitas }}</span></label>
                @endforeach
            </div>
        </div>
    </form>

    <div class="listing-toolbar clean-toolbar modern-results-toolbar">
        <div>
            <h2>{{ $kos->total() }} kos ditemukan</h2>
            <p>{{ request('area') ? 'Area ' . request('area') : 'Menampilkan semua area' }}</p>
        </div>
        <form action="{{ route('kos.index') }}" class="sort-box">
            @foreach(request()->except('sort', 'page') as $key => $value)
                @if(is_array($value))
                    @foreach($value as $item)<input type="hidden" name="{{ $key }}[]" value="{{ $item }}">@endforeach
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            <label>Urutkan</label>
            <select name="sort" onchange="this.form.submit()">
                @foreach($sortLabels as $value => $label)
                    <option value="{{ $value }}" @selected($activeSort === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="listing-results-list clean-results-list modern-results-list">
        @forelse($kos as $item)
            @include('components.kos-card', ['kos' => $item])
        @empty
            <div class="empty-state"><i class="bi bi-search-heart"></i><h4>Kos tidak ditemukan</h4><p>Coba ubah kata kunci, area, atau budget maksimal.</p><a href="{{ route('kos.index') }}" class="btn btn-primary">Lihat Semua Kos</a></div>
        @endforelse
    </div>

    <div class="mt-4 pagination-wrap">{{ $kos->links('pagination::bootstrap-5') }}</div>
</div>
@endsection

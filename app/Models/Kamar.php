<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kamar extends Model
{
    protected $fillable = [
        'kos_id', 'tipe_kamar', 'kode_kamar', 'lantai', 'luas_kamar', 'harga', 'harga_harian', 'harga_tahunan', 'jumlah_kamar', 'status', 'catatan', 'foto'
    ];

    protected $casts = [
        'luas_kamar' => 'decimal:2',
    ];

    public function kos(): BelongsTo
    {
        return $this->belongsTo(Kos::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getHargaRupiahAttribute(): string
    {
        return 'Rp' . number_format($this->harga, 0, ',', '.');
    }

    public function getHargaHarianRupiahAttribute(): string
    {
        return $this->harga_harian ? 'Rp' . number_format($this->harga_harian, 0, ',', '.') : '-';
    }

    public function getHargaTahunanRupiahAttribute(): string
    {
        return $this->harga_tahunan ? 'Rp' . number_format($this->harga_tahunan, 0, ',', '.') : '-';
    }

    public function getFotoUrlAttribute(): string
    {
        if (! $this->foto) {
            return optional($this->kos)->foto_url ?? asset('images/default-kos.jpg');
        }
        if (str_starts_with($this->foto, 'images/')) {
            return asset($this->foto);
        }
        return asset('storage/' . $this->foto);
    }
}

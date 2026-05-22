<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kos extends Model
{
    protected $fillable = [
        'user_id', 'nama_kos', 'tipe_kos', 'alamat', 'deskripsi', 'harga', 'no_wa', 'foto', 'status', 'lokasi_area', 'jarak_kampus', 'premium', 'verification_status'
    ];

    protected $casts = [
        'premium' => 'boolean',
        'jarak_kampus' => 'decimal:2',
    ];

    public function pemilik(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kamar(): HasMany
    {
        return $this->hasMany(Kamar::class);
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(KosFoto::class)->orderBy('urutan');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function fasilitas(): BelongsToMany
    {
        return $this->belongsToMany(Fasilitas::class, 'fasilitas_kos')->withTimestamps();
    }

    public function getHargaRupiahAttribute(): string
    {
        return 'Rp' . number_format($this->harga, 0, ',', '.');
    }

    public function getFotoUrlAttribute(): string
    {
        if (! $this->foto) {
            return asset('images/default-kos.jpg');
        }
        if (str_starts_with($this->foto, 'images/')) {
            return asset($this->foto);
        }
        return asset('storage/' . $this->foto);
    }

    public function getWhatsAppUrlAttribute(): string
    {
        $text = rawurlencode('Halo, saya tertarik dengan ' . $this->nama_kos . ' yang saya lihat di KosKu. Apakah masih tersedia?');
        return 'https://wa.me/' . preg_replace('/[^0-9]/', '', $this->no_wa) . '?text=' . $text;
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'tersedia' ? 'Ada Kosong' : 'Penuh';
    }

    public function getVerificationLabelAttribute(): string
    {
        return match ($this->verification_status) {
            'approved' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            default => 'Menunggu Verifikasi',
        };
    }

    public function getRatingAverageAttribute(): float
    {
        return round((float) $this->reviews()->avg('rating'), 1);
    }
}

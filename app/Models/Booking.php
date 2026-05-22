<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = ['user_id', 'kos_id', 'kamar_id', 'nama_pemesan', 'no_wa', 'tanggal_masuk', 'catatan', 'status', 'catatan_pemilik'];

    protected $casts = ['tanggal_masuk' => 'date'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function kos(): BelongsTo { return $this->belongsTo(Kos::class); }
    public function kamar(): BelongsTo { return $this->belongsTo(Kamar::class); }
    public function payment(): HasOne { return $this->hasOne(Payment::class); }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'success',
            'rejected' => 'danger',
            'cancelled' => 'secondary',
            default => 'warning',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'Diterima',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            default => 'Menunggu',
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = ['booking_id','user_id','kos_id','nominal','metode','status','bukti','jatuh_tempo','paid_at','catatan'];

    protected $casts = [
        'jatuh_tempo' => 'date',
        'paid_at' => 'datetime',
    ];

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function kos(): BelongsTo { return $this->belongsTo(Kos::class); }

    public function getNominalRupiahAttribute(): string
    {
        return 'Rp' . number_format($this->nominal, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'valid' => 'Valid',
            'pending' => 'Menunggu Validasi',
            'rejected' => 'Ditolak',
            default => 'Belum Bayar',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'valid' => 'success',
            'pending' => 'warning',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }
}

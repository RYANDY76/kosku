<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KosFoto extends Model
{
    protected $fillable = ['kos_id', 'path', 'caption', 'urutan', 'jenis'];

    public function kos(): BelongsTo
    {
        return $this->belongsTo(Kos::class);
    }

    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->path, 'images/')) {
            return asset($this->path);
        }
        return asset('storage/' . $this->path);
    }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis) {
            'kamar' => 'Foto Kamar',
            'dapur' => 'Foto Dapur',
            'parkiran' => 'Foto Parkiran',
            default => $this->caption ?: 'Foto Tambahan',
        };
    }
}

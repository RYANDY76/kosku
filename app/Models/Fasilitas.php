<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fasilitas extends Model
{
    protected $fillable = ['nama_fasilitas'];

    public function kos(): BelongsToMany
    {
        return $this->belongsToMany(Kos::class, 'fasilitas_kos')->withTimestamps();
    }
}

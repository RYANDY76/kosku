<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = ['user_id', 'kos_id', 'rating', 'komentar'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function kos(): BelongsTo { return $this->belongsTo(Kos::class); }
}

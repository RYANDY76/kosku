<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilePemilik extends Model
{
    protected $fillable = ['user_id', 'no_wa', 'alamat'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

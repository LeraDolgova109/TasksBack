<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TarotCardDescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
    ];

    protected $hidden = [];

    public function situations(): BelongsToMany
    {
        return $this->belongsToMany(Situation::class);
    }
}

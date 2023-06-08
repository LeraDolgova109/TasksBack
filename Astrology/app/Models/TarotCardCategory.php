<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarotCardCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $hidden = [];

    public function tarot_cards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TarotCard::class);
    }
}

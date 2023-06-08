<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Situation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tarot_card_id',
        'tarot_card_description_id'
    ];

    protected $hidden = [];

    public function tarot_card(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TarotCard::class);
    }
    public function tarot_card_description(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TarotCardDescription::class);
    }
}

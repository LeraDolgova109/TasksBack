<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TarotCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'standard_description',
        'tarot_card_category_id'
    ];

    protected $hidden = [];

    public function tarot_card_category(): BelongsTo
    {
        return $this->belongsTo(TarotCardCategory::class);
    }

    public function situations(): BelongsToMany
    {
        return $this->belongsToMany(Situation::class);
    }
}

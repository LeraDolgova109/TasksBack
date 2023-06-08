<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Horoscope extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'date'
    ];

    protected $hidden = [];


    public function zodiac_sign(): BelongsTo
    {
        return $this->belongsTo(ZodiacSign::class);
    }

}

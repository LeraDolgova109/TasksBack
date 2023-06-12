<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'creation_date',
        'deadline',
        'status',
        'progress',
        'is_important',
        'project_id',
        'category_id'
    ];

    protected $hidden = [
        'category_id',
        'created_at',
        'updated_at'
    ];

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function performers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Performer::class);
    }
}

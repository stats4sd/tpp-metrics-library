<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Developer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function metrics(): HasMany
    {
        return $this->hasMany(Metric::class);
    }

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class)->withTimestamps();
    }
}

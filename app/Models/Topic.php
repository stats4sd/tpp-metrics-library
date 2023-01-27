<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Topic extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_topic')
            ->withPivot('notes');
    }


    public function dimensions(): HasMany
    {
        return $this->hasMany(Dimension::class);
    }

    public function subDimensions(): HasManyThrough
    {
        return $this->hasManyThrough(SubDimension::class, Dimension::class);
    }
}

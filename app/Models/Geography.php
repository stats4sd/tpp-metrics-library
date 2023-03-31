<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Geography extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_geography')
            ->withPivot('notes');
    }

    public function references(): MorphToMany
    {
        return $this->morphToMany(Reference::class, 'referencable')
            ->withPivot('reference_type', 'notes', 'id');
    }
}

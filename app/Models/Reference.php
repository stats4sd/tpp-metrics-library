<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Reference extends Model
{
    use HasFactory;

    protected $guarded = [];


    // morph relationship to
    // Metrics (type === 'data source', computation guidance' or 'reference')
    // Collection Methods (type === 'reference')
    public function metrics(): MorphToMany
    {
        return $this->morphedByMany(Metric::class, 'referencable')
            ->withPivot('reference_type', 'notes', 'id');
    }

    public function collectionMethods(): MorphToMany
    {
        return $this->morphedByMany(CollectionMethod::class, 'referencable')
            ->withPivot('reference_type', 'notes', 'id');
    }

    public function dimensions(): MorphToMany
    {
        return $this->morphedByMany(Dimension::class, 'referencable')
            ->withPivot('reference_type', 'notes', 'id');
    }

    public function geographies(): MorphToMany
    {
        return $this->morphedByMany(Geography::class, 'referencable')
            ->withPivot('reference_type', 'notes', 'id');
    }

    public function scales(): MorphToMany
    {
        return $this->morphedByMany(Scale::class, 'referencable')
            ->withPivot('reference_type', 'notes', 'id');
    }

    public function referencables(): HasMany
    {
        return $this->hasMany(Referencable::class);
    }

}

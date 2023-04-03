<?php

namespace App\Models;

use App\Models\Referencable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reference extends Model
{
    use HasFactory;

    protected $guarded = [];


    // morph relationship to
    // Metrics (type === 'data source', computation guidance' or 'reference')
    // Collection Methods (type === 'reference')
    public function metrics(): MorphToMany
    {
        return $this->morphedByMany(Metric::class, 'referencable');
    }

    public function collectionMethods(): MorphToMany
    {
        return $this->morphedByMany(CollectionMethod::class,
        'referencable');
    }

    public function dimensions(): MorphToMany
    {
        return $this->morphedByMany(Dimension::class, 'referencable');
    }

    public function methods(): MorphToMany
    {
        return $this->morphedByMany(Method::class, 'referencable');
    }

    public function geographies(): MorphToMany
    {
        return $this->morphedByMany(Geography::class, 'referencable');
    }

    public function scales(): MorphToMany
    {
        return $this->morphedByMany(Scale::class, 'referencable');
    }

    public function referencables(): HasMany
    {
        return $this->hasMany(Referencable::class);
    }

}

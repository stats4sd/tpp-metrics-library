<?php

namespace App\Models;

use App\Models\Traits\GetRelationships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CollectionMethod extends Model
{
    use HasFactory, SoftDeletes, GetRelationships;

    protected $guarded = [];

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_collection_method')
            ->withPivot('relation_notes', 'needs_review')
            ->withTimestamps();
    }

    public function properties(): MorphToMany
    {
        return $this->morphToMany(Property::class, 'linked', 'property_links')
            ->withPivot('relation_notes')
            ->withTimestamps();
    }

    public function references(): MorphToMany
    {
        return $this->morphToMany(Reference::class, 'referencable')
            ->withPivot('reference_type', 'relation_notes', 'id')
            ->withTimestamps();
    }
}

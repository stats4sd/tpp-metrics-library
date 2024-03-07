<?php

namespace App\Models;

use App\Models\Traits\GetRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dimension extends Model
{
    use HasFactory, SoftDeletes, GetRelationships;

    protected $guarded = [];

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_dimension')
            ->withPivot('relation_notes', 'needs_review')
            ->withTimestamps();
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function references(): MorphToMany
    {
        return $this->morphToMany(Reference::class, 'referencable')
            ->withPivot('reference_type', 'relation_notes', 'id')
            ->withTimestamps();
    }

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class)->withTimestamps();
    }
}

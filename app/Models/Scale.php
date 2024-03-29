<?php

namespace App\Models;

use App\Models\Traits\GetRelationships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Scale extends Model
{
    use HasFactory, SoftDeletes, GetRelationships;

    protected $guarded = [];

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_scale')
            ->withPivot('relation_notes', 'commonly_used', 'type', 'needs_review')
            ->withTimestamps();
    }

    public function metricDecision(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_scale')
            ->withPivot('relation_notes', 'commonly_used', 'type', 'needs_review')
            ->wherePivot('type', '=', 'decision making')
            ->withTimestamps();
    }

    public function metricMeasurement(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_scale')
            ->withPivot('relation_notes', 'commonly_used', 'type', 'needs_review')
            ->wherePivot('type', '=', 'measurement')
            ->withTimestamps();
    }

    public function metricReporting(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_scale')
            ->withPivot('relation_notes', 'commonly_used', 'type', 'needs_review')
            ->wherePivot('type', '=', 'reporting')
            ->withTimestamps();
    }

    public function discussionPoints(): MorphMany
    {
        return $this->morphMany(DiscussionPoint::class, 'property_value');
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

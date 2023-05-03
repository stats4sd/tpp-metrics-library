<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Scale extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_scale')
            ->withPivot('relation_notes', 'commonly_used', 'type');
    }

    public function metricDecision(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_scale')
            ->withPivot('relation_notes', 'commonly_used', 'type')
            ->wherePivot('type', '=', 'decision making');
    }

    public function metricMeasurement(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_scale')
            ->withPivot('notes', 'commonly_used', 'type')
            ->wherePivot('type', '=', 'measurement');
    }

    public function metricReporting(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_scale')
            ->withPivot('relation_notes', 'commonly_used', 'type')
            ->wherePivot('type', '=', 'reporting');
    }

    public function discussionPoints(): MorphMany
    {
        return $this->morphMany(DiscussionPoint::class, 'property_value');
    }

    public function references(): MorphToMany
    {
        return $this->morphToMany(Reference::class, 'referencable')
            ->withPivot('reference_type', 'notes', 'id');
    }
}

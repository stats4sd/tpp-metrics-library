<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MetricUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_metric_user')
            ->withPivot('relation_notes', 'type', 'id', 'needs_review')
            ->withTimestamps();
    }

    public function metricDecisionMakers(): BelongsToMany
    {
        return $this->belongsToMany(
            Metric::class,
            'metric_metric_user'
        )
            ->withPivot('relation_notes', 'type', 'id', 'needs_review')
            ->wherePivot('type', '=', 'decision maker')
            ->withTimestamps();
    }

    public function metricCollectors(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_metric_user')
            ->withPivot('relation_notes', 'type', 'id', 'needs_review')
            ->wherePivot('type', '=', 'collector')
            ->withTimestamps();
    }

    public function metricImpactedBy(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_metric_user')
            ->withPivot('relation_notes', 'type', 'id', 'needs_review')
            ->wherePivot('type', '=', 'impacted by')
            ->withTimestamps();
    }

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class)->withTimestamps();
    }
}

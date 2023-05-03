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
            ->withPivot('relation_notes', 'type', 'id');
    }

    public function metricDecisionMakers(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class,
            'metric_metric_user')
            ->withPivot('relation_notes', 'type', 'id')
            ->wherePivot('type', '=', 'decision maker');
    }

    public function metricCollectors(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_metric_user')
            ->withPivot('relation_notes', 'type', 'id')
            ->wherePivot('type', '=', 'collector');
    }

    public function metricImpactedBy(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_metric_user')
            ->withPivot('relation_notes', 'type', 'id')
            ->wherePivot('type', '=', 'impacted by');
    }

}

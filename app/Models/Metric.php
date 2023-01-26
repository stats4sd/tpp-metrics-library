<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Metric extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    // relationships
    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class)
            ->withPivot('notes');
    }


    public function dimensions(): BelongsToMany
    {
        return $this->belongsToMany(Dimension::class)
            ->withPivot('notes');
    }

    public function subDimensions(): BelongsToMany
    {
        return $this->belongsToMany(SubDimension::class)
            ->withPivot('notes');
    }

    public function scaleDecision(): BelongsToMany
    {
        return $this->belongsToMany(Scale::class)
            ->wherePivot('type', '=', 'decision making')
            ->withPivot('notes', 'commonly_used', 'type');
    }

    public function scaleMeasurement(): BelongsToMany
    {
        return $this->belongsToMany(Scale::class)
            ->wherePivot('type', '=', 'measurement')
            ->withPivot('notes', 'commonly_used', 'type');

    }

    public function scaleReporting(): BelongsToMany
    {
        return $this->belongsToMany(Scale::class)
            ->wherePivot('type', '=', 'reporting')
            ->withPivot('notes', 'type');
    }

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class)
            ->withPivot('notes');
    }

    public function frameworks(): BelongsToMany
    {
        return $this->belongsToMany(Framework::class, 'metric_framework')
            ->withPivot('notes');
    }

    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'metric_unit')
            ->withPivot('notes');
    }

    public function complimentaryMetrics(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__)
            ->withPivot('notes');
    }



    public function metricProperties(): BelongsToMany
    {
        return $this->belongsToMany(MetricProperty::class)
            ->using(MetricPropertyLink::class)
            ->withPivot('notes');
    }

    public function collectionMethods(): HasMany
    {
        return $this->hasMany(CollectionMethod::class);

    }



    // Metric Users
    public function collectors(): BelongsToMany
    {
        return $this->belongsToMany(MetricUser::class)
            ->wherePivot('type', '=', 'collector')
            ->withPivot('notes', 'type');
    }

    public function decisionMakers(): BelongsToMany
    {
        return $this->belongsToMany(MetricUser::class)
            ->wherePivot('type', '=', 'decision maker')
            ->withPivot('notes', 'type');
    }

    public function impactedBy(): BelongsToMany
    {
        return $this->belongsToMany(MetricUser::class)
            ->wherePivot('type', '=', 'impacted')
            ->withPivot('notes', 'type');
    }






    public function altNames(): HasMany
    {
        return $this->hasMany(AltName::class);
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }

    public function farmingSystems(): BelongsToMany
    {
        return $this->belongsToMany(FarmingSystem::class)
            ->withPivot('notes');
    }

    public function geographies(): BelongsToMany
    {
        return $this->belongsToMany(Geography::class)
            ->withPivot('notes');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Metric extends Model
{
    use HasFactory;

    protected $guarded = [];


    // *************** 1.f DERIVED / RELATED METRICS ***************
    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function relatedMetrics(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    // ******************* 1.g COMPLIMENTARY METRICS ******************

    public function complimentaryMetrics(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'metric_metric', 'metric_id', 'related_id');
    }

    public function inverseComplimentaryMetrics(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'metric_metric', 'related_id', 'metric_id');
    }

    // 0.c Topics
    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'metric_topic')
            ->withPivot('notes');
    }

    // 0.d Dimensions
    public function dimensions(): BelongsToMany
    {
        return $this->belongsToMany(Dimension::class, 'metric_dimension')
            ->withPivot('notes');
    }

    // 0.e Sub-dimensions
    public function subDimensions(): BelongsToMany
    {
        return $this->belongsToMany(SubDimension::class, 'metric_sub_dimension')
            ->withPivot('notes');
    }

    // 0.f Scales (decisions)
    public function scaleDecision(): BelongsToMany
    {
        return $this->belongsToMany(Scale::class, 'metric_scale')
            ->wherePivot('type', '=', 'decision making')
            ->withPivot('notes', 'commonly_used', 'type');
    }

    // 1.c Scale - measurement
    public function scaleMeasurement(): BelongsToMany
    {
        return $this->belongsToMany(Scale::class, 'metric_scale')
            ->wherePivot('type', '=', 'measurement')
            ->withPivot('notes', 'commonly_used', 'type');

    }

    // 1.e Scale - reporting
    public function scaleReporting(): BelongsToMany
    {
        return $this->belongsToMany(Scale::class, 'metric_scale')
            ->wherePivot('type', '=', 'reporting')
            ->withPivot('notes', 'type', 'commonly_used');
    }

    // 0.g Tools
    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class, 'metric_tool')
            ->withPivot('notes');
    }

    // 0.h Frameworks
    public function frameworks(): BelongsToMany
    {
        return $this->belongsToMany(Framework::class, 'metric_framework')
            ->withPivot('notes');
    }

    // 1.d Units of measure
    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'metric_unit')
            ->withPivot('notes');
    }

    // 3. - Metric  Properties
    public function properties(): MorphToMany
    {
        return $this->morphToMany(Property::class, 'linked', 'property_links')
            ->withPivot('notes');
    }

    // 4.a Collection methods
    public function collectionMethods(): HasMany
    {
        return $this->hasMany(CollectionMethod::class);

    }

    // 6.a. ****** Use cases / users ********* //
    public function collectors(): BelongsToMany
    {
        return $this->belongsToMany(MetricUser::class)
            ->wherePivot('type', '=', 'collector')
            ->withPivot('notes', 'type', 'id');
    }

    public function decisionMakers(): BelongsToMany
    {
        return $this->belongsToMany(MetricUser::class)
            ->wherePivot('type', '=', 'decision maker')
            ->withPivot('notes', 'type', 'id');
    }

    public function impactedBy(): BelongsToMany
    {
        return $this->belongsToMany(MetricUser::class)
            ->wherePivot('type', '=', 'impacted by')
            ->withPivot('notes', 'type', 'id');
    }

    // ************************************** //

    // 0.b Alternative Names
    public function altNames(): HasMany
    {
        return $this->hasMany(AltName::class);
    }

    // 0.i Developer
    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }

    // 6.b Farming systems
    public function farmingSystems(): BelongsToMany
    {
        return $this->belongsToMany(FarmingSystem::class, 'metric_farming_system')
            ->withPivot('notes');
    }

    // 6.c Geographies
    public function geographies(): BelongsToMany
    {
        return $this->belongsToMany(Geography::class, 'metric_geography')
            ->withPivot('notes');
    }

    // 4.c Data sources
    public function dataSources(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referencable')
            ->where('references.type', '=', 'data source');
    }

    // 5.d. Computation guidance
    public function computationGuidance(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referencable')
            ->where('references.type', '=', 'computation guidance');
    }

    // 7. References
    public function references(): MorphMany
    {
        return $this->morphMany(Reference::class, 'referencable')
            ->where('references.type',  '=' , 'reference');

    }

    public function discussionPoints(): MorphMany
    {
        return $this->morphMany(DiscussionPoint::class, 'subject');
    }


}

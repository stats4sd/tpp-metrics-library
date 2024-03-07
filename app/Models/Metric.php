<?php

namespace App\Models;

use App\Models\Traits\GetRelationships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Metric extends Model
{
    use HasFactory, GetRelationships, SoftDeletes;

    protected $guarded = [];


    // *************** 1.f DERIVED / RELATED METRICS ***************
    public function childMetrics(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'metric_parent_child', 'parent_id', 'child_id')
            ->withPivot('relation_notes')
            ->withTimestamps();
    }

    // ******************* 1.g COMPLIMENTARY METRICS ******************

    public function complimentaryMetrics(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'metric_metric', 'metric_id', 'related_id')
            ->withPivot('relation_notes')
            ->withTimestamps();
    }

    public function inverseComplimentaryMetrics(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'metric_metric', 'related_id', 'metric_id')
            ->withPivot('relation_notes')
            ->withTimestamps();
    }

    // *************** 1.h PARENT METRICS ***************
    public function parentMetrics(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'metric_parent_child', 'child_id', 'parent_id')
            ->withPivot('relation_notes')
            ->withTimestamps();
    }


    // 0.c Topics
    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'metric_topic')
            ->withPivot('relation_notes')
            ->withTimestamps();
    }

    // 0.d Dimensions
    public function dimensions(): BelongsToMany
    {
        return $this->belongsToMany(Dimension::class, 'metric_dimension')
            ->withPivot('relation_notes', 'needs_review')
            ->withTimestamps();
    }

    // 0.e Sub-dimensions
    public function subDimensions(): BelongsToMany
    {
        return $this->belongsToMany(SubDimension::class, 'metric_sub_dimension')
            ->withPivot('relation_notes')
            ->withTimestamps();
    }

    // 0.f? Scales
    public function scales(): BelongsToMany
    {
        return $this->belongsToMany(Scale::class, 'metric_scale')
            ->wherePivot('type', '=', '')
            ->withPivot('relation_notes', 'commonly_used', 'type', 'needs_review')
            ->withTimestamps();
    }

    // 0.f Scales (decisions)
    public function scaleDecision(): BelongsToMany
    {
        return $this->belongsToMany(Scale::class, 'metric_scale')
            ->wherePivot('type', '=', 'decision making')
            ->withPivot('relation_notes', 'commonly_used', 'type', 'needs_review')
            ->withTimestamps();
    }

    // 1.c Scale - measurement
    public function scaleMeasurement(): BelongsToMany
    {
        return $this->belongsToMany(Scale::class, 'metric_scale')
            ->wherePivot('type', '=', 'measurement')
            ->withPivot('relation_notes', 'commonly_used', 'type', 'needs_review')
            ->withTimestamps();
    }

    // 1.e Scale - reporting
    public function scaleReporting(): BelongsToMany
    {
        return $this->belongsToMany(Scale::class, 'metric_scale')
            ->wherePivot('type', '=', 'reporting')
            ->withPivot('relation_notes', 'commonly_used', 'type', 'needs_review')
            ->withTimestamps();
    }

    // 0.g Tools
    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class, 'metric_tool')
            ->withPivot('relation_notes', 'needs_review')
            ->withTimestamps();
    }

    // 0.h Frameworks
    public function frameworks(): BelongsToMany
    {
        return $this->belongsToMany(Framework::class, 'metric_framework')
            ->withPivot('relation_notes', 'needs_review')
            ->withTimestamps();
    }

    // 1.d Units of measure
    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'metric_unit')
            ->withPivot('relation_notes')
            ->withTimestamps();
    }

    // 3. - Metric  Properties
    public function properties(): MorphToMany
    {
        return $this->morphToMany(Property::class, 'linked', 'property_links')
            ->withPivot('relation_notes')
            ->withTimestamps();
    }

    // 4.a Collection methods
    public function collectionMethods(): BelongsToMany
    {
        return $this->belongsToMany(CollectionMethod::class, 'metric_collection_method')
            ->withPivot('relation_notes', 'needs_review')
            ->withTimestamps();
    }

    // 6.a. ****** Use cases / users ********* //
    public function metricUsers(): BelongsToMany
    {
        return $this->belongsToMany(MetricUser::class)
            ->wherePivot('type', '=', '')
            ->withPivot('relation_notes', 'type', 'id', 'needs_review')
            ->withTimestamps();
    }

    public function collectors(): BelongsToMany
    {
        return $this->belongsToMany(MetricUser::class)
            ->wherePivot('type', '=', 'collector')
            ->withPivot('relation_notes', 'type', 'id', 'needs_review')
            ->withTimestamps();
    }

    public function decisionMakers(): BelongsToMany
    {
        return $this->belongsToMany(MetricUser::class)
            ->wherePivot('type', '=', 'decision maker')
            ->withPivot('relation_notes', 'type', 'id', 'needs_review')
            ->withTimestamps();
    }

    public function impactedBy(): BelongsToMany
    {
        return $this->belongsToMany(MetricUser::class)
            ->wherePivot('type', '=', 'impacted by')
            ->withPivot('relation_notes', 'type', 'id', 'needs_review')
            ->withTimestamps();
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
    // TODO: remove it
    public function farmingSystems(): BelongsToMany
    {
        return $this->belongsToMany(FarmingSystem::class, 'metric_farming_system')
            ->withPivot('relation_notes')
            ->withTimestamps();
    }

    // 6.c Geographies
    public function geographies(): BelongsToMany
    {
        return $this->belongsToMany(Geography::class, 'metric_geography')
            ->withPivot('relation_notes', 'needs_review')
            ->withTimestamps();
    }

    // 4.c Data sources
    public function dataSources(): MorphToMany
    {
        return $this->morphToMany(Reference::class, 'referencable')
            ->wherePivot('reference_type', '=', 'data source')
            ->withPivot('reference_type', 'relation_notes', 'id')
            ->withTimestamps();
    }

    // 5.d. Computation guidance
    public function computationGuidance(): MorphToMany
    {
        return $this->morphToMany(Reference::class, 'referencable')
            ->wherePivot('reference_type', '=', 'computation guidance')
            ->withPivot('reference_type', 'relation_notes', 'id')
            ->withTimestamps();
    }

    // 7. References
    public function references(): MorphToMany
    {
        return $this->morphToMany(Reference::class, 'referencable')
            ->wherePivot('reference_type',  '=', 'reference')
            ->withPivot('reference_type', 'relation_notes', 'id')
            ->withTimestamps();
    }

    public function discussionPoints(): MorphMany
    {
        return $this->morphMany(DiscussionPoint::class, 'subject');
    }

    public function themes(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class, 'metric_theme')
            ->withPivot('relation_notes', 'unreviewd_import')
            ->withTimestamps();
    }
}

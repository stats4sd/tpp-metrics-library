<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tool extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_tool')
            ->withPivot('relation_notes')
            ->withTimestamps();
    }

    public function frameworks(): BelongsToMany
    {
        return $this->belongsToMany(Framework::class, 'framework_tool')
            ->withPivot('relation_notes');
    }


    // ========== //


    public function references(): MorphToMany
    {
        return $this->morphToMany(Reference::class, 'referencable')
            ->withPivot('reference_type', 'relation_notes', 'id');
    }

    public function developers(): BelongsToMany
    {
        return $this->belongsToMany(Developer::class, 'developer_tool')
            ->withPivot('relation_notes', 'unreviewed_import');
    }

    public function framings(): BelongsToMany
    {
        return $this->belongsToMany(Framing::class, 'framing_tool')
            ->withPivot('relation_notes', 'unreviewed_import');
    }

    public function dimensions(): BelongsToMany
    {
        return $this->belongsToMany(Dimension::class, 'dimension_tool')
            ->withPivot('relation_notes', 'unreviewed_import');
    }

    public function themeSocial(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class, 'theme_tool')
            ->wherePivot('type', '=', 'social')
            ->withPivot('relation_notes', 'unreviewed_import', 'type');
    }

    public function themeEnviro(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class, 'theme_tool')
            ->wherePivot('type', '=', 'enviro')
            ->withPivot('relation_notes', 'unreviewed_import', 'type');
    }

    public function themeEconomic(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class, 'theme_tool')
            ->wherePivot('type', '=', 'economic')
            ->withPivot('relation_notes', 'unreviewed_import', 'type');
    }

    public function themeHuman(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class, 'theme_tool')
            ->wherePivot('type', '=', 'human')
            ->withPivot('relation_notes', 'unreviewed_import', 'type');
    }

    public function themeGov(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class, 'theme_tool')
            ->wherePivot('type', '=', 'gov')
            ->withPivot('relation_notes', 'unreviewed_import', 'type');
    }

    public function themeProduct(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class, 'theme_tool')
            ->wherePivot('type', '=', 'product')
            ->withPivot('relation_notes', 'unreviewed_import', 'type');
    }

    public function sdgs(): BelongsToMany
    {
        return $this->belongsToMany(Sdg::class, 'sdg_tool')
            ->withPivot('relation_notes', 'unreviewed_import');
    }

    public function metricUsers(): BelongsToMany
    {
        return $this->belongsToMany(MetricUser::class, 'metric_user_tool')
            ->withPivot('relation_notes', 'unreviewed_import');
    }

    public function scaleMeasurement(): BelongsToMany
    {
        return $this->belongsToMany(Scale::class, 'theme_tool')
            ->wherePivot('type', '=', 'measurement')
            ->withPivot('relation_notes', 'unreviewed_import', 'type');
    }

    public function scaleReporting(): BelongsToMany
    {
        return $this->belongsToMany(Scale::class, 'theme_tool')
            ->wherePivot('type', '=', 'reporting')
            ->withPivot('relation_notes', 'unreviewed_import', 'type');
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'country_tool')
            ->withPivot('relation_notes', 'unreviewed_import');
    }

    public function indicationSelections(): BelongsToMany
    {
        return $this->belongsToMany(IndicatorSelection::class, 'indication_selection_tool')
            ->withPivot('relation_notes', 'unreviewed_import');
    }

    public function dataTypes(): BelongsToMany
    {
        return $this->belongsToMany(DataType::class, 'data_type_tool')
            ->withPivot('relation_notes', 'unreviewed_import');
    }

    public function dataCollections(): BelongsToMany
    {
        return $this->belongsToMany(DataType::class, 'data_collection_tool')
            ->withPivot('relation_notes', 'unreviewed_import');
    }
}

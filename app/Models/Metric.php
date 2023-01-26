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

    // class relationships

    public function dimensions(): BelongsToMany
    {
        return $this->belongsToMany(Dimension::class)
            ->withPivot('notes');
    }

    public function metricProperties(): BelongsToMany
    {
        return $this->belongsToMany(MetricProperty::class)
            ->withPivot('notes');
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



    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class)
            ->withPivot('notes');
    }

    public function methods(): BelongsToMany
    {
        return $this->belongsToMany(Method::class, 'metric_method')
            ->withPivot('notes');
    }

    public function frameworks(): BelongsToMany
    {
        return $this->belongsToMany(Framework::class, 'metric_framework')
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

    public function altNames(): HasMany
    {
        return $this->hasMany(AltName::class);
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }
}

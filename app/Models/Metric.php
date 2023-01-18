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
        return $this->belongsToMany(Dimension::class, 'dimension_metric')
            ->withPivot('notes');
    }

    public function metricProperties(): BelongsToMany
    {
        return $this->belongsToMany(MetricProperty::class, 'metric_metric_property')
            ->withPivot('notes');
    }

    public function metricUsers(): BelongsToMany
    {
        return $this->belongsToMany(MetricUser::class, 'metric_metric_user')
            ->withPivot('notes');
    }

    public function metricTools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class, 'metric_tool')
            ->withPivot('notes');
    }

    public function metricMethods(): BelongsToMany
    {
        return $this->belongsToMany(Method::class, 'metric_method')
            ->withPivot('notes');
    }

    public function metricFrameworks(): BelongsToMany
    {
        return $this->belongsToMany(Framework::class, 'metric_framework')
            ->withPivot('notes');
    }

    public function metricScales(): BelongsToMany
    {
        return $this->belongsToMany(Scale::class, 'metric_scale')
            ->withPivot('notes', 'commonly_used');
    }

    public function altNames(): HasMany
    {
        return $this->hasMany(AltName::class);
    }
}

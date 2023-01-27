<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Scale extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_scale')
            ->withPivot('notes', 'commonly_used', 'type');
    }

    public function metricDescision(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_scale')
            ->withPivot('notes', 'commonly_used', 'type')
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
            ->withPivot('notes', 'commonly_used', 'type')
            ->wherePivot('type', '=', 'reporting');
    }
}

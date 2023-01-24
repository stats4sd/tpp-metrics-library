<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetricProperty extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_metric_property')
            ->withPivot('notes');
    }

    public function metricPropertyOptions(): HasMany
    {
        return $this->hasMany(MetricPropertyOption::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MetricPropertyOption extends Model
{
    use HasFactory;

    public function metricProperty(): BelongsTo
    {
        return $this->belongsTo(MetricProperty::class);
    }

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_metric_property_option');
    }

}

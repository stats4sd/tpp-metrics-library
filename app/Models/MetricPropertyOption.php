<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MetricPropertyOption extends Model
{
    use HasFactory;

    public function metricProperty(): BelongsTo
    {
        return $this->belongsTo(MetricProperty::class);
    }

}

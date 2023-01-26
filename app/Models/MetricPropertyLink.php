<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MetricPropertyLink extends Pivot
{
    public function selectedOptions(): BelongsToMany
    {
        return $this->belongsToMany(MetricPropertyOption::class);
    }

    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tool extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_tool')
            ->withPivot('notes');
    }

    public function frameworks(): BelongsToMany
    {
        return $this->belongsToMany(Framework::class, 'framework_tool')
            ->withPivot('notes');
    }
}

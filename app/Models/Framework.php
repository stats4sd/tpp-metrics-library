<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Framework extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_framework')
            ->withPivot('relation_notes');
    }

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class, 'framework_tool')
        ->withPivot('relation_notes');
    }

}

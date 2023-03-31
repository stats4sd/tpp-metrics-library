<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Method extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_method')
            ->withPivot('notes');
    }

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class, 'method_tool')
            ->withPivot('notes');
    }

    public function references(): MorphToMany
    {
        return $this->morphToMany(Reference::class, 'referencable')
            ->withPivot('reference_type', 'notes', 'id');
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dimension extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getFullNameAttribute()
    {
        return $this->topic->name . ' - ' . $this->name;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function dimensions(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    // class relationships

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'dimension_metric')
            ->withPivot('notes');
    }
}

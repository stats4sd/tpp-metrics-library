<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CollectionMethod extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_collection_method')
            ->withPivot('relation_notes');
    }

    public function properties(): MorphToMany
    {
        return $this->morphToMany(Property::class, 'linked', 'property_links')
            ->withPivot('relation_notes');
    }

    // TODO: include references

}

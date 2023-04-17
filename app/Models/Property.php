<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Property extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function metrics(): MorphToMany
    {
        return $this->morphedByMany(Metric::class, 'linked', 'property_links')
            ->using(PropertyLink::class)
            ->withPivot('relation_notes');
    }

    public function propertyLinks(): HasMany
    {
        return $this->hasMany(PropertyLink::class, 'property_id', 'id');
    }

    public function collectionMethods(): MorphToMany
    {
        return $this->morphedByMany(CollectionMethod::class, 'linked', 'property_links')
            ->using(PropertyLink::class)
            ->withPivot('relation_notes');
    }

    public function propertyOptions(): HasMany
    {
        return $this->hasMany(PropertyOption::class);
    }

}

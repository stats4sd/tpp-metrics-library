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
        return $this->morphedByMany(Metric::class, 'property_link')
            ->using(PropertyLink::class)
            ->withPivot('notes');
    }

    public function collectionMethods(): MorphToMany
    {
        return $this->morphedByMany(CollectionMethod::class, 'property_link')
            ->withPivot('notes');
    }

    public function propertyOptions(): HasMany
    {
        return $this->hasMany(PropertyOption::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

// Helper class. Allows us to link PropertyOptions to a specific metric/property (or collection method / property) link.
class PropertyLink extends Pivot
{

    protected $table = 'property_links';

    public function propertyOptions(): BelongsToMany
    {
        return $this->belongsToMany(PropertyOption::class, 'property_option_property_link', 'property_option_id', 'linked_id')
            ->withPivot('relation_notes');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }



}

<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

// Helper class. Allows us to link PropertyOptions to a specific metric/property (or collection method / property) link.
class PropertyLink extends Pivot
{
    use CrudTrait;


    public function propertyOptions(): BelongsToMany
    {
        return $this->belongsToMany(PropertyOption::class, 'property_option_property_link')
            ->withPivot('notes');
    }

    public function propertyLinkReference()
    {

    }




}

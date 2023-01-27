<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PropertyOption extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function propertyLnks(): BelongsToMany
    {
        return $this->belongsToMany(PropertyLink::class, 'property_option_property_link');
    }

}

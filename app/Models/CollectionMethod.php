<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class CollectionMethod extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function metric(): BelongsTo
    {
        return $this->belongsTo(Metric::class);
    }

    public function properties(): MorphToMany
    {
        return $this->morphToMany(Property::class, 'property_link')
            ->withPivot('notes');
    }


}

<?php

namespace App\Models;

use App\Models\Traits\GetRelationships;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dimension extends Model
{
    use HasFactory, SoftDeletes, GetRelationships;

    protected $guarded = [];

    // Soundex gives a lot of false positives, so should be billed as 'possible' duplicates.
    // A record has possible duplicates if there is another record with the same soundex value
    public function possibleDuplicates(): Attribute
    {
        return new Attribute(
            get: function (): bool {
                return self::where('soundex', $this->soundex)
                    ->where('id', '!=', $this->id)
                    ->exists();
            }
        );
    }

    // metaphone strings are much closer to the real text, so if 2 entries have the same metaphone they should be shown as 'likely' duplicates.
    public function likelyDuplicates(): Attribute
    {
        return new Attribute(
            get: function (): bool {
                return self::where('metaphone', $this->metaphone)
                    ->where('id', '!=', $this->id)
                    ->exists();
            }
        );
    }

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_dimension')
            ->withPivot('relation_notes', 'needs_review')
            ->withTimestamps();
    }

    public function references(): MorphToMany
    {
        return $this->morphToMany(Reference::class, 'referencable')
            ->withPivot('reference_type', 'relation_notes', 'id')
            ->withTimestamps();
    }

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class)->withTimestamps();
    }
}

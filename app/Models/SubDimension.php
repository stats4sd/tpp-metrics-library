<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubDimension extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function dimension(): BelongsTo
    {
        return $this->belongsTo(Dimension::class);
    }

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class);
    }
}

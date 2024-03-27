<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * "View on Sustainability" - for tools. (Chopin et al. (2021) categories for “view on sustainability”)
 */
class Framing extends Model
{

    protected $table = 'framings';

    protected $guarded = [];

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class)->withTimestamps();
    }
}

<?php

namespace App\Models;

use App\Models\DiscussionPoint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Flag extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function discussionPoints(): BelongsToMany
    {
        return $this->belongsToMany(DiscussionPoint::class);
    }
}

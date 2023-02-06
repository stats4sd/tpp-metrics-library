<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DiscussionPoint extends Model
{

    protected $guarded = [];

    public function getSubjectTypeLabelAttribute()
    {
        return str_replace('App\\Models\\', '', $this->subject_type);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}

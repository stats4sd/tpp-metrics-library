<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedbackFollowup extends Model
{
    protected $guarded = [];

    public function feedback(): BelongsTo
    {
        return $this->belongsTo(Feedback::class);
    }
}

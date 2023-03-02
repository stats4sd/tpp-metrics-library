<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DiscussionPoint extends Model
{

    protected $guarded = [];

    protected $appends = [
        'subject_type_label',
        'property_type_label',
    ];

    public function getSubjectTypeLabelAttribute()
    {
        return str_replace('App\\Models\\', '', $this->subject_type);
    }

    public function getPropertyTypeLabelAttribute()
    {
        return str_replace('App\\Models\\', '', $this->property_value_type);
    }

    public function markAsResolved()
    {
        $this->resolved_at = Carbon::now();
        $this->save;

        return $this;
    }

    public function markAsUnresolved()
    {
        $this->resolved_at = null;
        $this->save;

        return $this;
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function property_value(): MorphTo
    {
        return $this->morphTo('property_value');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}

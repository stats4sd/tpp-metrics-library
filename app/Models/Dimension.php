<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dimension extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function metrics()
    {
        return $this->belongsToMany(Metric::class)
            ->withPivot('notes');
    }
}

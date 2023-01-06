<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Method extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class)
            ->withPivot('notes');
    }

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class)
            ->withPivot('notes');
    }

}
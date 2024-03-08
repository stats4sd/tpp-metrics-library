<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sdg extends Model
{
    use HasFactory;

    protected $table = 'sdgs';

    protected $guarded = [];

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class)->withTimestamps();
    }
}

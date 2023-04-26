<?php

namespace App\Models;

use App\Models\Reference;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Referencable extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function references(): BelongsTo
    {
        return $this->belongsTo(Reference::class);
    }

}

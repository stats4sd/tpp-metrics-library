<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AltName extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $guarded = [];

    public function metric(): BelongsTo
    {
        return $this->belongsTo(Metric::class);
    }

}
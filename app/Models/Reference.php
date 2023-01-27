<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Reference extends Model
{
    use HasFactory;

    protected $guarded = [];


    // morph relationship to
    // Metrics (type === 'data source', computation guidance' or 'reference')
    // Collection Methods (type === 'reference')
    public function referencable(): MorphMany
    {
        $this->morphTo();
    }

    

}

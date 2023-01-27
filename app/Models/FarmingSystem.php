<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FarmingSystem extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $guarded = [];

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_farming_system')
            ->withPivot('notes');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Unit extends Model
{
    use HasFactory;

    protected $guarded = [];
//
//    protected function label(): Attribute
//    {
//        return Attribute::make(
//            get: fn($value) => "{$this->name} ( {$this->synbol} )",
//        );
//
//    }

    public function metrics(): BelongsToMany
    {
        return $this->belongsToMany(Metric::class, 'metric_unit')
            ->withPivot('notes');
    }
}

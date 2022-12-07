<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetricProperty extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function metrics()
    {
        return $this->belongsToMany(Metric::class)
            ->withPivot('notes');
    }

}

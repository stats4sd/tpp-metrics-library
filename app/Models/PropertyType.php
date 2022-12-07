<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class
PropertyType extends Model
{
    use HasFactory;
    protected $guarded = false;


    public function metricProperties()
    {
        return $this->hasMany(MetricProperty::class);
    }
}
